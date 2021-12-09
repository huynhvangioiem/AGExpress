<?php
    if(isset($_POST['action'])){
        include_once("connection.php");
        try {
            $get = mysqli_query($connect, "SELECT `AGEBoLID`,`AGEBoLDecs`, AGEPlaceName, `AGEBoLTransportFee`, `AGEBoLStatus` FROM `agebol` JOIN ageplace ON agebol.`AGEBoLEndPoint` = ageplace.AGEPlaceID") or die(mysqli_connect_error($connect));
            $res = "";
            include 'TLABarcode.php';
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){
                switch ($data['AGEBoLStatus']) {
                    case 0:
                        $status = "Đã nhập kho gửi";
                        break;
                    case 1:
                        $status = "Đang vận chuyển";
                        break;
                    case 2:
                        $status = "Đã đến kho phát";
                        break;
                    case 3:
                        $status = "Phát thành công";
                        break;
                    case 4:
                        $status = "Chờ phát lại";
                        break;
                    case 5:
                        $status = "Chuyển hoàn";
                        break;
                    case 6:
                        $status = "Đã chuyển hoàn";
                        break;
                    case -1:
                        $status = "Vô hiệu";
                        break;
                    
                };
	            $bolID =  createBar128($data['AGEBoLID']);
                $res .= '
                    <tr>
                        <td>'.$bolID.'</td>
                        <td>'.$data['AGEBoLDecs'].'</td>
                        <td>'.$data['AGEPlaceName'].'</td>
                        <td>'.number_format($data['AGEBoLTransportFee']).'</td>
                        <td>'.$status.'</td>
                        <td>
                            <button type="button" class="btn btn-info" onclick="detail(\''.$data["AGEBoLID"].'\')"><i class="fas fa-info"></i></button>
                            <button type="button" class="btn btn-warning" onclick="printer(\''.$data["AGEBoLID"].'\',\'bol\')"><i class="fas fa-print"></i></button>
                        </td>
                    </tr>
                ';
            }
            echo '
                <div class="col-12 col-m-12 col-s-12">
                    <table id="bolListTable" class="stripe" style="width:100%">
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
                        <tbody>'.$res.'</tbody>
                    </table>
                </div>
                <!-- Call DataTables -->
                <script>
                    $(document).ready(function () {
                        $("#bolListTable").DataTable({
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