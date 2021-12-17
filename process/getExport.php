<?php
    if(isset($_POST['action'])){ // check is set data
        include_once("connection.php");
        try { // try to query the bol in the database
            $get = mysqli_query($connect, "SELECT a.*, b.*, c.AGEPlaceName, d.AGEUserName, d.AGEUserFullName FROM ageexport a JOIN ageshipment b ON a.AGEShipment = b.AGEShipmentID JOIN ageplace c ON a.AGEDestination = c.AGEPlaceID JOIN AGEUser d ON a.AGEUser = d.AGEUserName") or die(mysqli_connect_error($connect));
            $res = "";
            include 'TLABarcode.php';
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
	            $exportID =  createBar128($data['AGEExportID']); // call create barcode
                $res .= '
                    <tr>
                        <td>'.$exportID.'</td>
                        <td>'.$data['AGEShipment'].'</td>
                        <td>'.date_format(date_create($data['AGEExportTime']),"d/m/Y H:i").'</td>
                        <td>'.$data['AGEPlaceName'].'</td>
                        <td>'.$data['AGEUserFullName'].'</td>
                        <td>'.$status.'</td>
                        <td>'.$action.'</td>
                    </tr>
                ';
            }
            // return the component
            echo '
                <div class="col-12 col-m-12 col-s-12">
                    <table id="exportListTable" class="stripe" style="width:100%">
                        <thead>
                            <tr>
                                <th>Mã Phiếu Xuất</th>
                                <th>Mã Chuyến</th>
                                <th>Thời Gian Xuất</th>
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
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
?>