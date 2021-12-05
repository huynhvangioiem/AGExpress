<?php
    if(isset($_POST['action'])){
        include_once("connection.php");
        try {
            $get = mysqli_query($connect, "SELECT * FROM `ageuser` JOIN ageusertype ON ageuser.AGEUserType = ageusertype.AGEUserTypeID JOIN ageplace ON ageuser.AGEPlace = ageplace.AGEPlaceID") or die(mysqli_connect_error($connect));
            $index = 1;
            $res = "";
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){
                $res .= '
                    <tr>
                        <td>'.$data['AGEUserName'].'</td>
                        <td>'.$data['AGEUserFullName'].'</td>
                        <td>'.$data['AGEUserTypeName'].'</td>
                        <td>'.$data['AGEPlaceName'].'</td>
                        <td>
                            <button type="button" class="btn btn-info" onclick="editUser(\''.$data["AGEUserName"].'\', \''.$data["AGEUserFullName"].'\', '.$data["AGEUserTypeID"].', '.$data["AGEPlaceID"].')"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-danger" onclick="deleteUser(\''.$data["AGEUserName"].'\')"><i class="far fa-trash-alt"></i></button>
                        </td>
                    </tr>
                ';
                $index++;
            }
            echo '
                <div class="col-12 col-m-12 col-s-12">
                    <table id="userListTable" class="stripe" style="width:100%">
                    <thead>
                        <tr>
                        <th>Số Điện Thoại</th>
                        <th>Họ Và Tên</th>
                        <th>Tài Khoản</th>
                        <th>Địa Điểm</th>
                        <th>Tùy Chọn</th>
                        </tr>
                    </thead>
                    <tbody>'.$res.'</tbody>
                    </table>
                </div>
                <!-- Call DataTables -->
                <script>
                    $(document).ready(function () {
                        $("#userListTable").DataTable({
                            "language": {
                                "emptyTable": "Không có dữ liệu trong bảng",
                                "info": "Hiển thị _START_ - _END_ trong số _TOTAL_ danh mục",
                                "infoEmpty": "Hiện thị 0 tài khoản",
                                "infoFiltered": "(Đã lọc từ _MAX_ danh mục)",
                                "lengthMenu": "Hiển thị _MENU_ dòng",
                                "search": "Tìm kiếm:",
                                "zeroRecords": "Không tìm thấy dữ liệu!",
                            }
                        });
                    });
                </script>
            ';
        } catch (\Throwable $th) {
            
        }
    }
?>
