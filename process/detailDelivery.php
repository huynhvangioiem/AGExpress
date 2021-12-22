<?php
session_start();
if (!isset($_SESSION['userName']) || !isset($_POST['action']) || !isset($_POST['deliveryID']) || !is_numeric($_POST['deliveryID'])) echo '<script>window.location ="/";</script>';
else {
  include("TLABarcode.php");
  include_once("connection.php");
  if ($_POST['action'] == "getInfo") getInfo($connect);
  if ($_POST['action'] == "getBol") getBol($connect);
}

function getInfo($connect)
{
  $deliveryID =  createBar128($_POST['deliveryID']);
  $get = mysqli_query($connect, "SELECT a.*, b.AGEUserName, b.AGEUserFullName FROM `agedelivery` a JOIN ageuser b ON a.AGEUser = b.AGEUserName WHERE a.AGEDeliveryID = '" . $_POST['deliveryID'] . "'") or die(mysqli_connect_error($connect));
  $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
  $d = substr($data['AGEDeliveryID'], strlen($data['AGEDeliveryID']) - 2, 2);
  $m = substr($data['AGEDeliveryID'], strlen($data['AGEDeliveryID']) - 4, 2);
  $y = substr($data['AGEDeliveryID'], strlen($data['AGEDeliveryID']) - 6, 2);
  $status = "";
  switch ($data['AGEDeliveryStatus']) {
    case 0:
      $status = "Chờ Phát";
      $action = '
          <button type="button" class="btn btn-success" onclick="addBol(' . $data['AGEDeliveryID'] . ')"><i class="fas fa-plus-circle"></i></button>
          <button type="button" class="btn btn-danger" onclick="delete(' . $data['AGEDeliveryID'] . ')"><i class="fas fa-trash-alt"></i></button>
      ';
      break;
    case 1:
      $status = "Đang phát";
      $action = '<button type="button" class="btn btn-info" onclick="detailDelivery(' . $data["AGEDeliveryID"] . ')"><i class="fas fa-info"></i></button>';
      break;
    case 2:
      $status = "Hoàn thành";
      $action = '<button type="button" class="btn btn-info" onclick="detailDelivery(' . $data["AGEDeliveryID"] . ')"><i class="fas fa-info"></i></button>';
      break;
  }
  echo '
    <div class="row">
      <div class="col-12 col-m-12 col-s-12">' . $deliveryID . '</div>
      <div class="col-4 col-m-4 col-s-4">
        <div class="row">
          <div class="col-5 col-m-5 col-s-5">Nhân viên phát: </div>
          <div class="col-7 col-m-7 col-s-7">' . $data['AGEUserFullName'] . '</div>
        </div>
      </div>
      <div class="col-4 col-m-4 col-s-4">
        <div class="row">
          <div class="col-5 col-m-5 col-s-5">Ngày phát: </div>
          <div class="col-7 col-m-7 col-s-7">' . $d . "/" . $m . "/20" . $y . '</div>
        </div>
      </div>
      <div class="col-4 col-m-4 col-s-4">
        <div class="row">
          <div class="col-5 col-m-5 col-s-5">Trạng thái: </div>
          <div class="col-7 col-m-7 col-s-7">' . $status . '</div>
        </div>
      </div>
    </div>
  ';
}
function getBol($connect){
  $get = mysqli_query($connect, "SELECT `AGEBol`, b.* FROM `agedeliverydetail` a JOIN agebol b ON a.AGEBol = b.AGEBoLID WHERE `AGEDelivery` = '".$_POST['deliveryID']."'");
  $getStt = mysqli_query($connect, "SELECT * FROM `agedelivery` WHERE `AGEDeliveryID` = '".$_POST['deliveryID']."'");
  $dataStt = mysqli_fetch_array($getStt, MYSQLI_ASSOC);
  $res = "";
  $action = "";
  switch ($dataStt['AGEDeliveryStatus']) {
    case 0:
      $action = '
        <button class="btn btn-success" type="button" onclick="delivery('.$_POST['deliveryID'].')">Xác Nhận</button>
        <button class="btn btn-warning" type="button" onclick="hideDialog(\'#addBolDialog\')">Đóng</button>
      ' ;
      break;
    case 1:
      $action = '
        <button class="btn btn-success" type="button" onclick="printDelivery()">In Danh Sách</button>
        <button class="btn btn-warning" type="button" onclick="hideDialog(\'#addBolDialog\')">Đóng</button>
      ';
      break;
    case 2:
      $action = '
        <button class="btn btn-warning" type="button" onclick="hideDialog(\'#addBolDialog\')">Đóng</button>
      ';
      break;
  }
  if($dataStt['AGEUser']!=$_SESSION['userName'])  $action = '
    <button class="btn btn-success" type="button" onclick="printDelivery()">In Danh Sách</button>
    <button class="btn btn-warning" type="button" onclick="hideDialog(\'#addBolDialog\')">Đóng</button>
  ';
  while ($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){
    //set status for bol
    switch ($dataStt['AGEDeliveryStatus']) {
      case 0:
        $actionBol = '<button type="button" class="btn btn-danger" onclick="deleteBol('.$_POST['deliveryID'].','.$data['AGEBol'].')"><i class="fas fa-trash-alt"></i></button>' ;
        break;
      case 1:
        $actionBol = '<button type="button" class="btn btn-success" onclick="finishBol('.$_POST['deliveryID'].','.$data['AGEBol'].')"><i class="fas fas fa-check"></i></button>';
        break;
      case 2:
        $actionBol = "";
        break;
    }
    if($dataStt['AGEUser']!=$_SESSION['userName']) $actionBol = "";
    $res.='
      <tr>
        <td>'.$data['AGEBol'].'</td>
        <td>'.$data['AGEBoLDecs'].'</td>
        <td>'.$data['AGEBoLReceiverName'].'</td>
        <td>'.$data['AGEBoLReceiverSdt'].'</td>
        <td>'.$data['AGEBoLReceiverAddress'].'</td>
        <td>Đang phát</td>
        <td>'.$actionBol.'</td>
      </tr>
    ';
  }
  ?>
    <table id="listBolTable" class="stripe" style="width:100%">
      <thead>
        <tr>
          <th>Mã Đơn</th>
          <th>Mô Tả</th>
          <th>Tên Người Nhận</th>
          <th>SĐT Người Nhận</th>
          <th>Địa Chỉ Phát</th>
          <th>Trạng Thái</th>
          <th>Tùy Chọn</th>
        </tr>
      </thead>
      <tbody><?php echo $res ?></tbody>
    </table>
    <!-- Call DataTables -->
    <script>
      $(document).ready(function() {
        $("#listBolTable").DataTable({
          "language": {
            "emptyTable": "Không có dữ liệu trong bảng",
            "info": "Hiển thị _START_ - _END_ / _TOTAL_ đơn hàng",
            "infoEmpty": "Hiện thị 0 đơn hàng",
            "infoFiltered": "(Đã lọc từ _MAX_ danh mục)",
            "lengthMenu": "Hiển thị _MENU_ dòng",
            "search": "Tìm kiếm:",
            "zeroRecords": "Không tìm thấy dữ liệu!",
          },
          "order": [0, "desc"]
        });
      });
    </script>
    <div class="row dialogFooter"><?php echo $action; ?></div>
  <?php
}
?>