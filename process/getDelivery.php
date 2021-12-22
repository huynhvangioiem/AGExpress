<?php
    /*
    * version 1.0
    * last Update: 20/12/21
    * start: 200 OK
    */
    session_start();
    //check login
    if( !isset($_SESSION['userName']) || !isset($_POST['action']) ) { // check login and input data
        echo '<script>window.location ="/";</script>';
    }else {// all right => process
        include_once("connection.php");
        $get = mysqli_query($connect, "SELECT a.*, b.AGEUserName, b.AGEUserFullName FROM `agedelivery` a JOIN ageuser b ON a.AGEUser = b.AGEUserName") or die(mysqli_connect_error($connect));
        $res = "";
        while ($data = mysqli_fetch_array($get, MYSQLI_ASSOC)) {
            $status="";
            $action = "";
            switch ($data['AGEDeliveryStatus']) {
                case 0: 
                    $status = "Chờ Phát";
                    $action = '
                        <button type="button" class="btn btn-success" onclick="addBol('.$data['AGEDeliveryID'].')"><i class="fas fa-plus-circle"></i></button>
                        <button type="button" class="btn btn-danger" onclick="del('.$data['AGEDeliveryID'].')"><i class="fas fa-trash-alt"></i></button>
                    ';
                    break;
                case 1:
                    $status = "Đang phát";
                    $action = '<button type="button" class="btn btn-info" onclick="detailDelivery('.$data["AGEDeliveryID"].')"><i class="fas fa-info"></i></button>';
                    break;
                case 2: 
                    $status = "Hoàn thành"; 
                    $action = '<button type="button" class="btn btn-info" onclick="detailDelivery('.$data["AGEDeliveryID"].')"><i class="fas fa-info"></i></button>';
                    break;
            }
            if($data['AGEUserName']!=$_SESSION['userName']) $action = '<button type="button" class="btn btn-info" onclick="detailDelivery('.$data["AGEDeliveryID"].')"><i class="fas fa-info"></i></button>';
            $d = substr($data['AGEDeliveryID'],strlen($data['AGEDeliveryID'])-2,2);
            $m = substr($data['AGEDeliveryID'],strlen($data['AGEDeliveryID'])-4,2);
            $y = substr($data['AGEDeliveryID'],strlen($data['AGEDeliveryID'])-6,2);
            $res.= '
                <tr>
                    <td>'.$data['AGEDeliveryID'].'</td>
                    <td>'.$d."/".$m."/20".$y.'</td>
                    <td>'.$data['AGEUserFullName'].'</td>
                    <td>'.$status.'</td>
                    <td>'.$action.'</td>
                </tr>
            ';
        }
        echo '
            <div class="col-12 col-m-12 col-s-12">
                <table id="deliveryListTable" class="stripe" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mã Danh Sách Phát</th>
                            <th>Ngày Phát</th>
                            <th>Nhân Viên Phát</th>
                            <th>Trạng Thái</th>
                            <th>Tùy Chọn</th>
                        </tr>
                    </thead>
                    <tbody>'.$res.'</tbody>
                </table>
            </div>
            <!-- Call DataTables -->
            <script>
                $(document).ready(function () {
                    $("#deliveryListTable").DataTable({
                        "language": {
                            "emptyTable": "Không có dữ liệu trong bảng",
                            "info": "Hiển thị _START_ - _END_ trong số _TOTAL_ danh mục",
                            "infoEmpty": "Hiện thị 0 tài khoản",
                            "infoFiltered": "(Đã lọc từ _MAX_ danh mục)",
                            "lengthMenu": "Hiển thị _MENU_ dòng",
                            "search": "Tìm kiếm:",
                            "zeroRecords": "Không tìm thấy dữ liệu!",
                        },
                        "order": [ 0, "desc" ]
                    });
                });
            </script>
        ';
    }
?>