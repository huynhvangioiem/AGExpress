<?php
if(isset($_POST['idUserType']) && isset($_POST['nameUserType'])){ //check is set input
    if(is_numeric($_POST['idUserType']) && is_string($_POST['nameUserType']) && $_POST['nameUserType']!=""){ // check input value
        include_once("connection.php");
        try { // try to delete type and aler if success
            mysqli_query($connect, "DELETE FROM `ageusertype` WHERE `ageusertype`.`AGEUserTypeID` =".$_POST['idUserType']) or die(mysqli_connect_error($connect));
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thành Công!',
                            message: 'Đã xóa loại tài khoản ".$_POST["nameUserType"].".',
                            style: 'success-outline',
                            duration: 5000,
                            iconType: 'success',
                        });
                    })
                </script> 
            ";
        } catch (\Throwable $th) { // if wrong, aler error message
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thất Bại!',
                            message: 'Không thể xóa loại tài khoản này!',
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