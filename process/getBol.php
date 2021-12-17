<?php
    if(isset($_POST['action'])){ // check is set data
        include_once("connection.php");
        try { // try to query the bol in the database
            $get = mysqli_query($connect, "SELECT `AGEBoLID`, `AGEBoLDecs`, b.AGEPlaceName, `AGEBoLEndPoint`, `AGEBoLTransportFee`, `AGEBoLStatus`, c.AGEPlace FROM agebol a JOIN ageplace b ON a.AGEBoLEndPoint = b.AGEPlaceID JOIN ageuser c ON a.AGEUser = c.AGEUserName") or die(mysqli_connect_error($connect));
            $res = "";
            include 'TLABarcode.php';
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ //browse through each data
                //set status
                if($data['AGEBoLStatus']==$data['AGEPlace']) $status = "Đã nhập kho gửi";
                else if($data['AGEBoLStatus']==$data['AGEBoLEndPoint']) $status = "Đang phát";
                else $status = "Đang vận chuyển";
                // call create barcode
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
            // return the component
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