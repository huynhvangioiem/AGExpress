<?php
if(isset($_POST['id']) && isset($_POST['name'])){ //check is set input data
    if(is_numeric($_POST['id']) && is_string($_POST['name']) && $_POST['name']!=""){ // check input value
        include_once("connection.php");
        $sqlQuery = mysqli_prepare($connect, "DELETE FROM `ageplace` WHERE `AGEPlaceID` = ".$_POST['id']);
        if(mysqli_stmt_execute($sqlQuery)){
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
        }else{
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