<?php
session_start();
if( isset($_POST['userName']) && isset($_POST['oldPassword']) && isset($_POST['newPassword']) ){ //check is set data
    if( // check input value
        is_string($_POST['userName'])   && $_POST['userName']   != '' &&
        is_string($_POST['oldPassword'])   && $_POST['oldPassword']   != '' &&
        is_string($_POST['newPassword'])   && $_POST['newPassword']   != ''
    ){
        include_once("connection.php");
        try { // try to check the user within the old password
            $get = mysqli_query($connect, "SELECT * FROM `ageuser` WHERE `AGEUserName` = '".$_POST['userName']."' AND `AGEUserPassword` = '".md5($_POST['oldPassword'])."'") or die(mysqli_connect_error($connect));
            if (mysqli_num_rows($get) == 1) { //if the user with old password is exist
                try { // try to update old password to new password, destroy session and aler success
                    mysqli_query($connect, "UPDATE `ageuser` SET `AGEUserPassword`='".md5($_POST['newPassword'])."' ,`AGEUserStatus`= 1 WHERE `AGEUserName` = '".$_POST['userName']."'") or die(mysqli_connect_error($connect));
                    session_destroy(); 
                    echo "
                        <script>
                            $(document).ready(() => {
                                toast({
                                    title: 'Thành Công!',
                                    message: 'Mật khẩu đã được cập nhật. Vui lòng đăng nhập lại để tiếp tục',
                                    style: 'success-outline',
                                    duration: 5000,
                                    iconType: 'success',
                                });
                            })
                            setTimeout(function(){
                                window.location = 'login.html';
                            }, 3000);
                        </script> 
                    ";
                } catch (\Throwable $th) { //if the update is wrong, aler error message
                    echo "
                        <script>
                            $(document).ready(() => {
                                toast({
                                    title: 'Thất Bại!',
                                    message: 'Đã có lỗi. Vui lòng kiểm tra lại.',
                                    style: 'danger-outline',
                                    duration: 5000,
                                    iconType: 'danger',
                                });
                            })
                        </script> 
                    ";
                }
            }else{ //else if the user with old password is not exist, aler the old password is wrong
                echo "
                    <script>
                        $(document).ready(() => {
                            toast({
                                title: 'Thất Bại!',
                                message: 'Mật khẩu cũ không chính xác! Vui lòng kiểm tra lại.',
                                style: 'danger-outline',
                                duration: 5000,
                                iconType: 'danger',
                            });
                        })
                    </script> 
                ";
            }
        } catch (\Throwable $th) {// aler error message if wrong
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thất Bại!',
                            message: 'Đã có lỗi. Vui lòng kiểm tra lại.',
                            style: 'danger-outline',
                            duration: 5000,
                            iconType: 'danger',
                        });
                    })
                </script> 
            ";
        }
    }
}
?>