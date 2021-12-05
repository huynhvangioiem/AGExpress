<?php
if(isset($_POST['userName'])){
    if(is_string($_POST['userName'])&& $_POST['userName']!=''){
        include_once("connection.php");
        try {
            mysqli_query($connect, "DELETE FROM `ageuser` WHERE `ageuser`.`AGEUserName` = '".$_POST["userName"]."'") or die(mysqli_connect_error($connect));
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thành Công!',
                            message: 'Đã xóa tài khoản ".$_POST["userName"].".',
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
                            message: 'Không thể xóa tài khoản này!',
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