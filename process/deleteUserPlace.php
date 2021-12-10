<?php
if(isset($_POST['id']) && isset($_POST['name'])){ //check is set input data
    if(is_numeric($_POST['id']) && is_string($_POST['name']) && $_POST['name']!=""){ // check input value
        include_once("connection.php");
        try { // try to delete place and if aler success
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
        } catch (\Throwable $th) { // if wrong, aler error message
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