<?php
    /*
    * version 1.0
    * Last Update: 21/12/21
    * Status: 200 OK
    */
    session_start();
    if( !isset($_SESSION['userName']) || !isset($_POST['action']) ) echo '<script>window.location ="/";</script>';
    else{
        include_once("connection.php");
        $get = mysqli_query($connect, "SELECT a.*, b.AGEPlaceName as placeFrom, c.AGEPlaceName as placeTo FROM `ageshipment` a JOIN ageplace b ON a.`AGEShipmentFrom` = b.AGEPlaceID JOIN ageplace c ON a.`AGEShipmentTo` = c.AGEPlaceID") or die(mysqli_connect_error($connect));
        $res = "";
        $getPermissions = mysqli_query($connect, "SELECT `AGEUserPermissShipment` FROM `ageuserpermiss` WHERE `AGEUser` = '".$_SESSION['userName']."'");
        $dataPermis=mysqli_fetch_array($getPermissions, MYSQLI_ASSOC);
        while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ //browse through each data
            $action = "";
            switch ($data['AGEShipmentStatus']) { //set status
                case 0:
                    $status = "Đã lên lịch";
                    $action = '
                        <button type="button" class="btn btn-success" onclick="play(\''.$data["AGEShipmentID"].'\')"><i class="far fa-play-circle"></i></button>
                        <button type="button" class="btn btn-info" onclick="edit(\''.$data["AGEShipmentID"].'\', \''.$data["AGEShipmentDriverName"].'\', \''.$data['AGEShipmentBKS'].'\', '.$data['AGEShipmentFrom'].', '.$data['AGEShipmentTo'].', \''.date_format(date_create($data['AGEShipmentStart']),"Y-m-d\TH:i").'\', \''.date_format(date_create($data['AGEShipmentEnd']),"Y-m-d\TH:i").'\')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-warning" onclick="cancel(\''.$data["AGEShipmentID"].'\')"><i class="far fa-trash-alt"></i></button>
                    ';
                    break;
                case 1:
                    $status = "Đang vận chuyển";
                    $action = '
                        <button type="button" class="btn btn-success" onclick="done(\''.$data["AGEShipmentID"].'\')"><i class="fas fa-check"></i></button>
                    ';
                    break;
                case 2:
                    $status = "Hoàn thành";
                    $action = "";
                    break;
                case -1:
                    $status = "Đã hủy";
                    $action = "";
                    break;
                
            };
            $res .= '
                <tr>
                    <td>'.$data['AGEShipmentID'].'</td>
                    <td>'.$data['AGEShipmentDriverName'].'</td>
                    <td>'.$data['AGEShipmentBKS'].'</td>
                    <td>'.$data['placeFrom'].' - '.$data['placeTo'].'</td>
                    <td>'.date_format(date_create($data['AGEShipmentStart']),"d/m/Y H:i").'</td>
                    <td>'.date_format(date_create($data['AGEShipmentEnd']),"d/m/Y H:i").'</td>
                    <td>'.$status.'</td>';
            if($dataPermis['AGEUserPermissShipment'] == 1) $res.="<td>$action</td>";
            $res.='
                </tr>
            ';
        }
        // return the component
        echo '
        <div class="col-12 col-m-12 col-s-12">
            <table id="shipmentListTable" class="stripe" style="width:100%">
                <thead>
                    <tr>
                        <th>Mã Chuyến</th>
                        <th>Tên Tài Xế</th>
                        <th>Số Xe</th>
                        <th>Lộ Trình</th>
                        <th>Khởi Hành</th>
                        <th>Đến Nơi (Dự Kiến)</th>
                        <th>Trạng Thái</th>
        ';
        if($dataPermis['AGEUserPermissShipment'] == 1) echo "<th>Tùy Chọn</th>";
        echo '
                    </tr>
                </thead>
                <tbody>'.$res.'</tbody>
            </table>
        </div>
        <!-- Call DataTables -->
        <script>
            $(document).ready(function () {
                $("#shipmentListTable").DataTable({
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