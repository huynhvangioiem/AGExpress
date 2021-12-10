<?php
//check isset data post
if( isset($_POST['userName']) && isset($_POST['password']) && isset($_POST['fullName']) && isset($_POST['userType']) && isset($_POST['userPlace']) && isset($_POST['permiss']) ){
    if( // check value data
        is_string($_POST['userName'])   && $_POST['userName']   != '' &&
        is_string($_POST['password'])   && $_POST['password']   != '' &&
        is_string($_POST['fullName'])   && $_POST['fullName']   != '' &&
        is_numeric($_POST['userType'])  && $_POST['userType']   != '' &&
        is_numeric($_POST['userPlace']) && $_POST['userPlace']  != '' &&
        is_array($_POST['permiss']) && $_POST['permiss']  != []
    ){ //data is true
        $password = md5($_POST['password']);
        include_once("connection.php");
        try { // try to add user to database and aler if success
            mysqli_query($connect, "INSERT INTO `ageuser`(`AGEUserName`, `AGEUserPassword`, `AGEUserFullName`, `AGEUserType`, `AGEPlace`, `AGEUserStatus`)
                                    VALUES ('".$_POST['userName']."','$password','".$_POST['fullName']."',".$_POST['userType'].",".$_POST['userPlace'].",0)") or die(mysqli_connect_error($connect));
            
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thành Công!',
                            message: 'Tài khoản \"".$_POST['userName']."\" đã được đăng ký thành công.',
                            style: 'success-outline',
                            duration: 5000,
                            iconType: 'success',
                        });
                    })
                </script> 
            ";
        } catch (\Throwable $th) { //if don't success
            try { //try to find the user with user is input data
                $get = mysqli_query($connect, "SELECT * FROM `ageuser` WHERE `AGEUserName` = '".$_POST['userName']."'") or die(mysqli_connect_error($connect));
                if (mysqli_num_rows($get) == 1) { //if the user is exist then aler message error
                    echo "
                        <script>
                            $(document).ready(() => {
                                toast({
                                    title: 'Thất Bại!',
                                    message: 'Tài khoản \"".$_POST['userName']."\" đã được đăng ký.',
                                    style: 'danger-outline',
                                    duration: 5000,
                                    iconType: 'danger',
                                });
                            })
                        </script> 
                    "; 
                }
            } catch (\Throwable $th) { // if the process find the user is wrong, aler the message error
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
}
?>