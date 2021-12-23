<?php
  session_start();
  if( !isset($_SESSION['userName']) || !isset($_POST['timeStart']) || !isset($_POST['timeEnd']) || !isset($_POST['reportAction']) ) echo '<script>window.location ="/";</script>';
  else{
    include_once("connection.php");
    if($_POST['reportAction']=="sales"){
      $start = substr($_POST['timeStart'],2,2).substr($_POST['timeStart'],5,2).substr($_POST['timeStart'],8,2)."%";
      $end = substr($_POST['timeEnd'],2,2).substr($_POST['timeEnd'],5,2).(substr($_POST['timeEnd'],8,2)+1)."%";
      $get = mysqli_query($connect, "SELECT a.AGEBoLID, a.AGEBoLDecs, a.AGEBoLEndPoint, a.AGEBoLTransportFee, a.AGEBoLStatus, b.AGEPlaceName endPointName, c.AGEPlace placeCreate, d.AGEPlaceName placeCreateName
      FROM agebol a JOIN ageplace b ON a.AGEBoLEndPoint = b.AGEPlaceID JOIN ageuser c ON a.AGEUser = c.AGEUserName JOIN ageplace d ON c.AGEPlace = d.AGEPlaceID WHERE `AGEBoLID` >= '$start' AND `AGEBoLID` < '$end'") or die(mysqli_connect_error($connect));
      $res = '';
      $totals = 0;
      while ($data = mysqli_fetch_array($get, MYSQLI_ASSOC)) {
        if($data['AGEBoLStatus']==$data['placeCreate']) $status = "Đã nhập kho gửi";
        else if($data['AGEBoLStatus']==$data['AGEBoLEndPoint']) $status = "Đang phát";
        else if($data['AGEBoLStatus']==200) $status = "Phát thành công";
        else $status = "Đang vận chuyển";
        $totals+=$data['AGEBoLTransportFee'];
        $res .= '
          <tr>
              <td>'.$data['AGEBoLID'].'</td>
              <td>'.$data['AGEBoLDecs'].'</td>
              <td>'.$data['placeCreateName'].'</td>
              <td>'.$data['endPointName'].'</td>
              <td>'.number_format($data['AGEBoLTransportFee']).'</td>
              <td>'.$status.'</td>
          </tr>
        ';
      }
      echo '
        <div class="col-12 col-m-12 col-s-12">
          <table id="ListTable" class="stripe" style="width:100%">
            <thead>
              <tr>
                <th>Mã Đơn</th>
                <th>Mô Tả</th>
                <th>Gửi Từ</th>
                <th>Gửi Đến</th>
                <th>Phí Vận Chuyển (VNĐ)</th>
                <th>Trạng Thái</th>
              </tr>
            </thead>
            <tbody>' . $res . '</tbody>
            <tfoot>
              <tr>
                <td>Tổng Cộng:</td>
                <td></td>
                <td></td>
                <td></td>
                <td>'.number_format($totals).' VNĐ</td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- Call DataTables -->
        <script>
          $(document).ready(function () {
            $("#ListTable").DataTable({
              "language": {
              "emptyTable": "Không có dữ liệu trong bảng",
              "info": "Hiển thị _START_ - _END_ trong số _TOTAL_ danh mục",
              "infoEmpty": "Hiện thị 0 tài khoản",
              "infoFiltered": "(Đã lọc từ _MAX_ danh mục)",
              "lengthMenu": "Hiển thị _MENU_ dòng",
              "search": "Tìm kiếm:",
              "zeroRecords": "Không tìm thấy dữ liệu!",
              }
            });
          });
        </script>
      ';
    }
  }
?>