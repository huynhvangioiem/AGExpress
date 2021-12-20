<?php
    session_start();
    if(isset($_POST['action'])){ // check is set data
        include_once("connection.php");
        try { // try to query the bol in the database
            $get = mysqli_query($connect, "SELECT a.*,b.AGEUserFullName as nvNhap FROM (
                SELECT ageexport.AGEExportID, ageexport.AGEShipment, ageexport.AGEExportTime, ageuser.AGEUserFullName, ageexport.AGEExportStatus, ageimport.AGEImportTime, ageimport.AGEUser FROM ageexport JOIN ageuser ON ageexport.AGEUser = ageuser.AGEUserName LEFT JOIN ageimport ON ageexport.AGEExportID = ageimport.AGEExport WHERE ageexport.AGEDestination = ( SELECT ageuser.AGEPlace FROM ageuser WHERE ageuser.AGEUserName = '".$_SESSION['userName']."' )
            ) a JOIN ageuser b ON a.AGEUser = b.AGEUserName") or die(mysqli_connect_error($connect));
            $res = "";
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ //browse through each data
                switch ($data['AGEExportStatus']) { //set status
                    case 1:
                        $status = "Đang vận chuyển";
                        $action = '<button type="button" class="btn btn-success" onclick="checkExport('.$data["AGEExportID"].')"><i class="fas fa-sign-in-alt"></i></button>';
                        break;
                    case 2:
                        $status = "Đã Nhập";
                        $action = '<button type="button" class="btn btn-info" onclick="checkExport('.$data["AGEExportID"].')"><i class="fas fa-info"></i></button>';
                        break;
                };
                $res .= '
                    <tr>
                        <td>'.$data['AGEExportID'].'</td>
                        <td>'.$data['AGEShipment'].'</td>
                        <td>'.date_format(date_create($data['AGEExportTime']),"d/m/Y H:i").'</td>
                        <td>'.$data['AGEUserFullName'].'</td>
                        <td>'.$data['AGEImportTime'].'</td>
                        <td>'.$data['nvNhap'].'</td>
                        <td>'.$status.'</td>
                        <td>'.$action.'</td>
                    </tr>
                ';
            }
            // return the component
            echo '
                <div class="col-12 col-m-12 col-s-12">
                    <table id="importListTable" class="stripe" style="width:100%">
                        <thead>
                            <tr>
                                <th>Mã Phiếu Xuất</th>
                                <th>Mã Chuyến</th>
                                <th>Thời Gian Xuất</th>
                                <th>Nhân Viên Xuất</th>
                                <th>Thời Gian Nhập</th>
                                <th>Nhân Viên Nhập</th>
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
                        $("#importListTable").DataTable({
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
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
?>