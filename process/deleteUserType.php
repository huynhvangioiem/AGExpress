<?php
if(isset($_POST['idUserType']) && isset($_POST['nameUserType'])){
    if(is_numeric($_POST['idUserType']) && is_string($_POST['nameUserType']) && $_POST['nameUserType']!=""){
        include_once("connection.php");
        try {
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
        } catch (\Throwable $th) {
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