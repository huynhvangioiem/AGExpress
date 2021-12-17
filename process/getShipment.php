<?php
    if(isset($_POST['action'])){ // check is set data
        include_once("connection.php");
        try { // try to query the bol in the database
            $get = mysqli_query($connect, "SELECT a.*, b.AGEPlaceName as placeFrom, c.AGEPlaceName as placeTo FROM `ageshipment` a JOIN ageplace b ON a.`AGEShipmentFrom` = b.AGEPlaceID JOIN ageplace c ON a.`AGEShipmentTo` = c.AGEPlaceID") or die(mysqli_connect_error($connect));
            $res = "";
            include 'TLABarcode.php';
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ //browse through each data
                $disabled = "";
                switch ($data['AGEShipmentStatus']) { //set status
                    case 0:
                        $status = "Đã lên lịch";
                        break;
                    case 1:
                        $status = "Đang vận chuyển";
                        $disabled = "disabled";
                        break;
                    case 2:
                        $status = "Hoàn thành";
                        $disabled = "disabled";
                        break;
                    case -1:
                        $status = "Đã hủy";
                        $disabled = "disabled";
                        break;
                    
                };
	            $shipmentID =  createBar128($data['AGEShipmentID']); // call create barcode
                $res .= '
                    <tr>
                        <td>'.$shipmentID.'</td>
                        <td>'.$data['AGEShipmentDriverName'].'</td>
                        <td>'.$data['AGEShipmentBKS'].'</td>
                        <td>'.$data['placeFrom'].' - '.$data['placeTo'].'</td>
                        <td>'.date_format(date_create($data['AGEShipmentStart']),"d/m/Y H:i").'</td>
                        <td>'.date_format(date_create($data['AGEShipmentEnd']),"d/m/Y H:i").'</td>
                        <td>'.$status.'</td>
                        <td>
                            <button type="button" class="btn btn-success" '.$disabled.' onclick="play(\''.$data["AGEShipmentID"].'\')"><i class="far fa-play-circle"></i></button>
                            <button type="button" class="btn btn-info" '.$disabled.' onclick="edit(\''.$data["AGEShipmentID"].'\', \''.$data["AGEShipmentDriverName"].'\', \''.$data['AGEShipmentBKS'].'\', '.$data['AGEShipmentFrom'].', '.$data['AGEShipmentTo'].', \''.date_format(date_create($data['AGEShipmentStart']),"Y-m-d\TH:i").'\', \''.date_format(date_create($data['AGEShipmentEnd']),"Y-m-d\TH:i").'\')"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-warning" '.$disabled.' onclick="cancel(\''.$data["AGEShipmentID"].'\')"><i class="far fa-trash-alt"></i></button>
                        </td>
                    </tr>
                ';
            }
            // return the component
            echo '
                <div class="col-12 col-m-12 col-s-12">
                    <table id="shipmentListTable" class="stripe" style="width:100%">
                        <thead>
                            <tr>
                                <th>Mã Đơn</th>
                                <th>Tên Tài Xế</th>
                                <th>Số Xe</th>
                                <th>Lộ Trình</th>
                                <th>Khởi Hành</th>
                                <th>Đến Nơi (Dự Kiến)</th>
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
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
?>