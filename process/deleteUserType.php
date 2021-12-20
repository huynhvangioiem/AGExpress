<?php
if(isset($_POST['idUserType']) && isset($_POST['nameUserType'])){ //check is set input
    if(is_numeric($_POST['idUserType']) && is_string($_POST['nameUserType']) && $_POST['nameUserType']!="" ){ // check input value
        include_once("connection.php");
        $sqlQuery = mysqli_prepare($connect, "DELETE FROM `ageusertype` WHERE `AGEUserTypeID` = '".$_POST['idUserType']."'");
        if(mysqli_stmt_execute($sqlQuery)){
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
        }else{
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