<?php
if (isset($_POST['exportID']) && isset($_POST['action'])) {
  include_once("connection.php");
  include 'TLABarcode.php';
  if (is_numeric($_POST['exportID']) && $_POST['action'] == 'getExportInfo') {
    getInfo($_POST['exportID'], $connect);
  }
  if (is_numeric($_POST['exportID']) && $_POST['action'] == 'getBolInExport') {
    getBolInExport($_POST['exportID'], $connect);
  }
  if (is_numeric($_POST['exportID']) && $_POST['action'] == 'getBolInExportToCheck') {
    getBolInExportToCheck($_POST['exportID'], $connect);
  }
}

function getBolInExportToCheck($id, $connect)
{
  try {
    $getStatusExp = mysqli_query($connect, "SELECT `AGEExportStatus` FROM ageexport WHERE `AGEExportID` = '$id'") or die(mysqli_connect_error($connect));
    $dataStt = mysqli_fetch_array($getStatusExp, MYSQLI_ASSOC);
    switch ($dataStt['AGEExportStatus']) { //set status
      case 1:
        $action = '
        <button class="btn btn-success" type="button" onclick="importBol('.$id.')">Nhập Kho</button>
          <button class="btn btn-warning" type="button" onclick="hideDialog(\'#checkExportDialog\')">Đóng</button>
        ';
        break;
        case 2:
          $action = '
          <button class="btn btn-warning" type="button" onclick="hideDialog(\'#checkExportDialog\')">Đóng</button>
        ';
        break;
    };
    $res = "";
    $get = mysqli_query($connect, "SELECT b.AGEBoLID, b.AGEBoLDecs, d.AGEPlaceName, b.AGEBoLTransportFee, b.AGEBoLStatus, b.AGEBoLEndPoint, c.AGEPlace FROM ageexportdetail a JOIN agebol b ON a.AGEBoL = b.AGEBoLID JOIN ageuser c ON b.AGEUser = c.AGEUserName JOIN ageplace d ON b.AGEBoLEndPoint = d.AGEPlaceID WHERE a.AGEExport = '$id'") or die(mysqli_connect_error($connect));
    while ($data = mysqli_fetch_array($get, MYSQLI_ASSOC)) { //browse through each data
      if ($data['AGEBoLStatus'] == $data['AGEPlace']) $status = "Đã nhập kho gửi";
      else if ($data['AGEBoLStatus'] == $data['AGEBoLEndPoint']) $status = "Đang phát";
      else $status = "Đang vận chuyển";
      $res .= '
        <tr>
            <td>' . $data['AGEBoLID'] . '</td>
            <td>' . $data['AGEBoLDecs'] . '</td>
            <td>' . $data['AGEPlaceName'] . '</td>
            <td>' . number_format($data['AGEBoLTransportFee']) . '</td>
            <td>' . $status . '</td>
            <td><input class="form-check-input" type="checkbox" disabled id="'.$data['AGEBoLID'].'" name="'.$data['AGEBoLID'].'" value="true"></td>
        </tr>
      ';
    }
?>
    <table id="listBolTable" class="stripe" style="width:100%">
      <thead>
        <tr>
          <th>Mã Đơn</th>
          <th>Mô Tả</th>
          <th>Địa Điểm Phát</th>
          <th>Phí Vận Chuyển (VNĐ)</th>
          <th>Trạng Thái</th>
          <th>Check</th>
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
  } catch (\Throwable $th) {
  }
};

function getBolInExport($id, $connect)
{
  try {
    $getStatusExp = mysqli_query($connect, "SELECT `AGEExportStatus` FROM ageexport WHERE `AGEExportID` = '$id'") or die(mysqli_connect_error($connect));
    $dataStt = mysqli_fetch_array($getStatusExp, MYSQLI_ASSOC);
    switch ($dataStt['AGEExportStatus']) { //set status
      case -1:
        $disabled = "disabled";
        $action = '<button class="btn btn-warning" type="button" onclick="hideDialog(\'#addBolToExportDialog\')">Đóng</button>';
        break;
      case 1:
        $disabled = "disabled";
        $action = '
            <button class="btn btn-info" type="button" onclick="printExport()">In Phiếu Xuất</button>
            <button class="btn btn-warning" type="button" onclick="hideDialog(\'#addBolToExportDialog\')">Đóng</button>
          ';
        break;
      case 2:
        $disabled = "disabled";
        $action = '
            <button class="btn btn-info" type="button" onclick="printExport()">In Phiếu Xuất</button>
            <button class="btn btn-warning" type="button" onclick="hideDialog(\'#addBolToExportDialog\')">Đóng</button>
          ';
        break;
      case 0:
        $disabled = "";
        $action = '
            <button class="btn btn-success" type="button" onclick="exportBol(' . $_POST['exportID'] . ')">Xuất Kho</button>
            <button class="btn btn-danger" type="button" onclick="cancel(' . $_POST['exportID'] . ')">Hủy Xuất</button>
            <button class="btn btn-warning" type="button" onclick="hideDialog(\'#addBolToExportDialog\')">Đóng</button>  
          ';
        break;
    };
    $res = "";
    $get = mysqli_query($connect, "SELECT b.AGEBoLID, b.AGEBoLDecs, d.AGEPlaceName, b.AGEBoLTransportFee, b.AGEBoLStatus, b.AGEBoLEndPoint, c.AGEPlace FROM ageexportdetail a JOIN agebol b ON a.AGEBoL = b.AGEBoLID JOIN ageuser c ON b.AGEUser = c.AGEUserName JOIN ageplace d ON b.AGEBoLEndPoint = d.AGEPlaceID WHERE a.AGEExport = '$id'") or die(mysqli_connect_error($connect));
    while ($data = mysqli_fetch_array($get, MYSQLI_ASSOC)) { //browse through each data
      if ($data['AGEBoLStatus'] == $data['AGEPlace']) $status = "Đã nhập kho gửi";
      else if ($data['AGEBoLStatus'] == $data['AGEBoLEndPoint']) $status = "Đang phát";
      else $status = "Đang vận chuyển";
      $bolID =  createBar128($data['AGEBoLID']); // call create barcode
      $res .= '
        <tr>
            <td>' . $bolID . '</td>
            <td>' . $data['AGEBoLDecs'] . '</td>
            <td>' . $data['AGEPlaceName'] . '</td>
            <td>' . number_format($data['AGEBoLTransportFee']) . '</td>
            <td>' . $status . '</td>
            <td><button type="button" '.$disabled.' class="btn btn-warning" onclick="del(' . $data["AGEBoLID"] . ',' . $id . ')"><i class="far fa-trash-alt"></i></button></td>
        </tr>
      ';
    }
?>
    <table id="listBolTable" class="stripe" style="width:100%">
      <thead>
        <tr>
          <th>Mã Đơn</th>
          <th>Mô Tả</th>
          <th>Địa Điểm Phát</th>
          <th>Phí Vận Chuyển (VNĐ)</th>
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
  } catch (\Throwable $th) {
  }
};

function getInfo($id, $connect)
{
  try {
    $get = mysqli_query($connect, "SELECT a.AGEExportID, a.AGEExportTime, a.AGEShipment, a.AGEExportStatus, b.AGEShipmentDriverName, b.AGEShipmentStart, b.AGEShipmentEnd, c.AGEPlaceName, d.AGEUserFullName, e.AGEPlaceName placeFrom, f.AGEPlaceName placeTo FROM ageexport a JOIN ageshipment b ON a.AGEShipment = b.AGEShipmentID JOIN ageplace c ON a.AGEDestination = c.AGEPlaceID JOIN ageuser d ON a.AGEUser = d.AGEUserName JOIN ageplace e ON b.AGEShipmentFrom = e.AGEPlaceID JOIN ageplace f ON b.AGEShipmentTo = f.AGEPlaceID WHERE `AGEExportID` = '$id'") or die(mysqli_connect_error($connect));
    $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
    switch ($data['AGEExportStatus']) { //set status
      case -1:
        $status = "Đã Hủy";
        break;
      case 1:
        $status = "Đang vận chuyển";
        break;
      case 2:
        $status = "Hoàn thành";
        break;
      case 0:
        $status = "Chờ xuất";
        break;
    };
  ?>
    <div class="row">
      <div class="col-12 col-m-12 col-s-12"><?php echo createBar128($id) ?></div>
      <div class="col-6 col-m-6 col-s-6">
        <div class="row">
          <div class="col-4 col-m-4 col-s-5">Trạng Thái: </div>
          <div class="col-8 col-m-8 col-s-7"><?php echo $status ?></div>
          <div class="col-4 col-m-4 col-s-5">Nhân viên xuất: </div>
          <div class="col-8 col-m-8 col-s-7"><?php echo $data['AGEUserFullName'] ?></div>
          <div class="col-4 col-m-4 col-s-5">Xuất đến: </div>
          <div class="col-8 col-m-8 col-s-7"><?php echo $data['AGEPlaceName'] ?></div>
          <div class="col-4 col-m-4 col-s-5">Thời gian xuất: </div>
          <div class="col-8 col-m-8 col-s-7"><?php echo date_format(date_create($data['AGEExportTime']), "d/m/Y H:i") ?></div>
        </div>
      </div>
      <div class="col-6 col-m-6 col-s-6">
        <div class="row">
          <div class="col-4 col-m-4 col-s-5">Mã chuyến xe: </div>
          <div class="col-8 col-m-8 col-s-7"><?php echo $data['AGEShipment'] ?></div>
          <div class="col-4 col-m-4 col-s-5">Tài Xế: </div>
          <div class="col-8 col-m-8 col-s-7"><?php echo $data['AGEShipmentDriverName'] ?></div>
          <div class="col-4 col-m-4 col-s-5">Lộ Trình: </div>
          <div class="col-8 col-m-8 col-s-7"><?php echo $data['placeFrom'] . " - " . $data['placeTo'] ?></div>
          <div class="col-4 col-m-4 col-s-5">Thời gian: </div>
          <div class="col-8 col-m-8 col-s-7"><?php echo date_format(date_create($data['AGEShipmentStart']), "d/m/Y H:i") . " - " . date_format(date_create($data['AGEShipmentEnd']), "d/m/Y H:i") ?></div>
        </div>
      </div>
    </div>
<?php
  } catch (\Throwable $th) {
  }
}
?>