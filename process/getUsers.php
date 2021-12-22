<?php
/*
* version 1.0
* Last Update: 20/12/21
* Status: 200 OK
*/
session_start();
if (!isset($_SESSION['userName']) || !isset($_POST['action'])) echo '<script>window.location ="/";</script>';
else {
    include_once("connection.php");
    $get = mysqli_query($connect, "SELECT * FROM `ageuser` JOIN ageusertype ON ageuser.AGEUserType = ageusertype.AGEUserTypeID JOIN ageplace ON ageuser.AGEPlace = ageplace.AGEPlaceID") or die(mysqli_connect_error($connect));
    $res = "";
    $getPermissions = mysqli_query($connect, "SELECT AGEUserPermissUser FROM `ageuserpermiss` WHERE `AGEUser` = '" . $_SESSION['userName'] . "'") or die(mysqli_connect_error($connect));
    $dataPermissions = mysqli_fetch_array($getPermissions, MYSQLI_ASSOC);
    if ($dataPermissions['AGEUserPermissUser'] == 0) {
        while ($data = mysqli_fetch_array($get, MYSQLI_ASSOC)) {
            switch ($data['AGEUserStatus']) {
                case 0:
                    $status = "Chưa kích hoạt";
                    $action = '
                        <button type="button" class="btn btn-info" onclick="editUser(\'' . $data["AGEUserName"] . '\', \'' . $data["AGEUserFullName"] . '\', ' . $data["AGEUserTypeID"] . ', ' . $data["AGEPlaceID"] . ')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger" onclick="deleteUser(\'' . $data["AGEUserName"] . '\')"><i class="far fa-trash-alt"></i></button>
                    ';
                    break;
                case 1:
                    $status = "Hoạt động";
                    $action = '
                        <button type="button" class="btn btn-info" onclick="editUser(\'' . $data["AGEUserName"] . '\', \'' . $data["AGEUserFullName"] . '\', ' . $data["AGEUserTypeID"] . ', ' . $data["AGEPlaceID"] . ')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-warning" onclick="lockUser(\'' . $data["AGEUserName"] . '\')"><i class="fas fa-lock"></i></button>
                        <button type="button" class="btn btn-danger" onclick="deleteUser(\'' . $data["AGEUserName"] . '\')"><i class="far fa-trash-alt"></i></button>
                    ';
                    break;
                case -1:
                    $status = "Vô hiệu";
                    $action = '
                        <button type="button" class="btn btn-info" onclick="editUser(\'' . $data["AGEUserName"] . '\', \'' . $data["AGEUserFullName"] . '\', ' . $data["AGEUserTypeID"] . ', ' . $data["AGEPlaceID"] . ')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-warning" onclick="unlockUser(\'' . $data["AGEUserName"] . '\')"><i class="fas fa-unlock"></i></button>
                        <button type="button" class="btn btn-danger" onclick="deleteUser(\'' . $data["AGEUserName"] . '\')"><i class="far fa-trash-alt"></i></button>
                    ';
                    break;
            };
            $res .= '
                <tr>
                    <td>' . $data['AGEUserName'] . '</td>
                    <td>' . $data['AGEUserFullName'] . '</td>
                    <td>' . $data['AGEUserTypeName'] . '</td>
                    <td>' . $data['AGEPlaceName'] . '</td>
                    <td>' . $status . '</td>
                </tr>
            ';
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
                    <th>Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>' . $res . '</tbody>
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
    } else {
        while ($data = mysqli_fetch_array($get, MYSQLI_ASSOC)) {
            switch ($data['AGEUserStatus']) {
                case 0:
                    $status = "Chưa kích hoạt";
                    $action = '
                        <button type="button" class="btn btn-info" onclick="editUser(\'' . $data["AGEUserName"] . '\', \'' . $data["AGEUserFullName"] . '\', ' . $data["AGEUserTypeID"] . ', ' . $data["AGEPlaceID"] . ')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-danger" onclick="deleteUser(\'' . $data["AGEUserName"] . '\')"><i class="far fa-trash-alt"></i></button>
                    ';
                    break;
                case 1:
                    $status = "Hoạt động";
                    $action = '
                        <button type="button" class="btn btn-info" onclick="editUser(\'' . $data["AGEUserName"] . '\', \'' . $data["AGEUserFullName"] . '\', ' . $data["AGEUserTypeID"] . ', ' . $data["AGEPlaceID"] . ')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-warning" onclick="lockUser(\'' . $data["AGEUserName"] . '\')"><i class="fas fa-lock"></i></button>
                        <button type="button" class="btn btn-danger" onclick="deleteUser(\'' . $data["AGEUserName"] . '\')"><i class="far fa-trash-alt"></i></button>
                    ';
                    break;
                case -1:
                    $status = "Vô hiệu";
                    $action = '
                        <button type="button" class="btn btn-info" onclick="editUser(\'' . $data["AGEUserName"] . '\', \'' . $data["AGEUserFullName"] . '\', ' . $data["AGEUserTypeID"] . ', ' . $data["AGEPlaceID"] . ')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-warning" onclick="unlockUser(\'' . $data["AGEUserName"] . '\')"><i class="fas fa-unlock"></i></button>
                        <button type="button" class="btn btn-danger" onclick="deleteUser(\'' . $data["AGEUserName"] . '\')"><i class="far fa-trash-alt"></i></button>
                    ';
                    break;
            };
            $res .= '
                <tr>
                    <td>' . $data['AGEUserName'] . '</td>
                    <td>' . $data['AGEUserFullName'] . '</td>
                    <td>' . $data['AGEUserTypeName'] . '</td>
                    <td>' . $data['AGEPlaceName'] . '</td>
                    <td>' . $status . '</td>
                    <td>' . $action . '</td>
                </tr>
            ';
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
                        <th>Trạng Thái</th>
                        <th>Tùy Chọn</th>
                        </tr>
                    </thead>
                    <tbody>' . $res . '</tbody>
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
    }
}
?>
