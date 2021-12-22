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
        $get = mysqli_query($connect, "SELECT a.*, b.AGEShipmentID, c.AGEPlaceName detinationName, d.AGEUserFullName, e.AGEPlaceName placeCreate FROM ageexport a JOIN ageshipment b ON a.AGEShipment = b.AGEShipmentID JOIN ageplace c ON a.AGEDestination = c.AGEPlaceID JOIN ageuser d ON a.AGEUser = d.AGEUserName JOIN ageplace e ON d.AGEPlace = e.AGEPlaceID") or die(mysqli_connect_error($connect));
        $res = "";
        while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ //browse through each data
            switch ($data['AGEExportStatus']) { //set status
                case -1:
                    $status = "Đã Hủy";
                    $action = '<button type="button" class="btn btn-info" onclick="detailExport('.$data["AGEExportID"].')"><i class="fas fa-info"></i></button>';
                    break;
                case 1:
                    $status = "Đang vận chuyển";
                    $action = '<button type="button" class="btn btn-info" onclick="detailExport('.$data["AGEExportID"].')"><i class="fas fa-info"></i></button>';
                    break;
                case 2:
                    $status = "Hoàn thành";
                    $action = '<button type="button" class="btn btn-info" onclick="detailExport('.$data["AGEExportID"].')"><i class="fas fa-info"></i></button>';
                    break;
                case 0:
                    $status = "Chờ xuất";
                    $action = '
                        <button type="button" class="btn btn-success" onclick="addBolToExport('.$data["AGEExportID"].')"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-primary" onclick="edit(\''.$data["AGEExportID"].'\', \''.date_format(date_create($data['AGEExportTime']),"Y-m-d\TH:i").'\', \''.$data["AGEShipmentID"].'\', '.$data['AGEDestination'].')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-warning" onclick="cancel('.$data["AGEExportID"].')"><i class="far fa-trash-alt"></i></button>
                    ';
                    break;
            };
            if($data['AGEUser']!=$_SESSION['userName']){
                $action = '<button type="button" class="btn btn-info" onclick="detailExport('.$data["AGEExportID"].')"><i class="fas fa-info"></i></button>';
            }
            $res .= '
                <tr>
                    <td>'.$data['AGEExportID'].'</td>
                    <td>'.$data['AGEShipment'].'</td>
                    <td>'.date_format(date_create($data['AGEExportTime']),"d/m/Y H:i").'</td>
                    <td>'.$data['placeCreate'].'</td>
                    <td>'.$data['detinationName'].'</td>
                    <td>'.$data['AGEUserFullName'].'</td>
                    <td>'.$status.'</td>
                    <td>'.$action.'</td>
                </tr>
            ';
        }
        echo '
            <div class="col-12 col-m-12 col-s-12">
                <table id="exportListTable" class="stripe" style="width:100%">
                    <thead>
                        <tr>
                            <th>Mã Phiếu Xuất</th>
                            <th>Mã Chuyến</th>
                            <th>Thời Gian Xuất</th>
                            <th>Nơi Xuất</th>
                            <th>Xuất Đến</th>
                            <th>Nhân Viên Xuất</th>
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
                    $("#exportListTable").DataTable({
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