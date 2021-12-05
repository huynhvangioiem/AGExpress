<?php
if(isset($_POST['id']) && isset($_POST['name'])){
    if(is_numeric($_POST['id']) && is_string($_POST['name']) && $_POST['name']!=""){
        include_once("connection.php");
        try {
            mysqli_query($connect, "DELETE FROM `ageplace` WHERE `AGEPlaceID` = ".$_POST['id']) or die(mysqli_connect_error($connect));
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thành Công!',
                            message: 'Điểm GD ".$_POST["name"]." đã được xóa.',
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
                            message: 'Không thể xóa điểm GD này!',
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