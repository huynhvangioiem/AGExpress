<?php
    session_start();
    if(isset($_POST['action'])){ // check is set data
        include_once("connection.php");
        try { // try to query the bol in the database
            $get = mysqli_query($connect, "SELECT a.*, b.AGEUserFullName exporter, c.AGEPlaceName placeExport, d.AGEPlaceName destinationName, e.AGEImportTime, e.AGEUser, f.AGEUserFullName importer FROM ageexport a JOIN ageuser b ON a.AGEUser = b.AGEUserName JOIN ageplace c ON b.AGEPlace = c.AGEPlaceID JOIN ageplace d ON a.AGEDestination = d.AGEPlaceID LEFT JOIN ageimport e ON e.AGEExport = a.AGEExportID LEFT JOIN ageuser f ON f.AGEUserName = e.AGEUser WHERE `AGEExportStatus` = 1 OR `AGEExportStatus` = 2") or die(mysqli_connect_error($connect));
            $res = "";

            $getPermissions = mysqli_query($connect, "SELECT a.AGEUserPermissImport, b.AGEPlace FROM `ageuserpermiss` a JOIN ageuser b ON a.AGEUser = b.AGEUserName WHERE a.AGEUser = '".$_SESSION['userName']."'");
            $dataPermissions = mysqli_fetch_array($getPermissions, MYSQLI_ASSOC);

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
                if($dataPermissions['AGEPlace']!=$data['AGEDestination']) $action = "";
                $importTime = $data['AGEImportTime'] ? date_format(date_create($data['AGEImportTime']),"d/m/Y H:i") : "";
                $res .= '
                    <tr>
                        <td>'.$data['AGEExportID'].'</td>
                        <td>'.$data['AGEShipment'].'</td>
                        <td>'.date_format(date_create($data['AGEExportTime']),"d/m/Y H:i").'</td>
                        <td>'.$data['placeExport'].'</td>
                        <td>'.$data['exporter'].'</td>
                        <td>'.$importTime.'</td>
                        <td>'.$data['destinationName'].'</td>
                        <td>'.$data['importer'].'</td>
                        <td>'.$status.'</td>';
                if($dataPermissions['AGEUserPermissImport']==1) $res.='<td>'.$action.'</td>';
                $res.='
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
                                <th>Kho Xuất</th>
                                <th>Nhân Viên Xuất</th>
                                <th>Thời Gian Nhập</th>
                                <th>Kho Nhập</th>
                                <th>Nhân Viên Nhập</th>
                                <th>Trạng Thái</th>';
            if($dataPermissions['AGEUserPermissImport']==1) 
                echo'<th>Tùy Chọn</th>';
            echo '
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