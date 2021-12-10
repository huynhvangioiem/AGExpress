<?php
if(isset($_POST['id']) && isset($_POST['place']) && isset($_POST['address'])){ // check is set data
    if(is_numeric($_POST['id']) && is_string($_POST['place']) && $_POST['place']!=""  && is_string($_POST['address']) && $_POST['address']!=""){ // check value input
        include_once("connection.php");
        try { // try to update place and aler is success
            mysqli_query($connect, "UPDATE `ageplace` SET `AGEPlaceName`='".$_POST["place"]."',`AGEPlaceAddress`='".$_POST['address']."' WHERE `AGEPlaceID` = ".$_POST['id']) or die(mysqli_connect_error($connect));
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thành Công!',
                            message: 'Đã cập nhật điểm GD thành công.',
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
                            message: 'Có lỗi xãy ra. Vui lòng thử lại!',
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