<?php
    /*
    * version 1.0
    * last Update: 20/12/21
    * start: 200 OK
    */
    session_start();
    //check login
    if( !isset($_SESSION['userName']) || !isset($_POST['action']) || $_POST['action'] !== 'getBol' ) { // check login and input data
        echo '<script>window.location ="/";</script>';
    }else {// all right => process
        include_once("connection.php");
        $get = mysqli_query($connect, "
            SELECT a.AGEBoLID, a.AGEBoLDecs, a.AGEBoLEndPoint, a.AGEBoLTransportFee, a.AGEBoLStatus, b.AGEPlaceName endPointName, c.AGEPlace placeCreate, d.AGEPlaceName placeCreateName
            FROM agebol a JOIN ageplace b ON a.AGEBoLEndPoint = b.AGEPlaceID JOIN ageuser c ON a.AGEUser = c.AGEUserName JOIN ageplace d ON c.AGEPlace = d.AGEPlaceID
        ") or die(mysqli_connect_error($connect));
        $res = "";
        while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ //browse through each data
            //set status
            if($data['AGEBoLStatus']==$data['placeCreate']) $status = "Đã nhập kho gửi";
            else if($data['AGEBoLStatus']==$data['AGEBoLEndPoint']) $status = "Đang phát";
            else $status = "Đang vận chuyển";
            $res .= '
                <tr>
                    <td>'.$data['AGEBoLID'].'</td>
                    <td>'.$data['AGEBoLDecs'].'</td>
                    <td>'.$data['placeCreateName'].'</td>
                    <td>'.$data['endPointName'].'</td>
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
                            <th>Nơi Chấp Nhận Gửi</th>
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
                            "emptyTable": "Không tồn tại đơn hàng!",
                            "info": "Hiển thị _START_ - _END_ / _TOTAL_ đơn hàng",
                            "infoEmpty": "Hiện thị 0 đơn hàng",
                            "infoFiltered": "(Đã lọc từ _MAX_ đơn hàng)",
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