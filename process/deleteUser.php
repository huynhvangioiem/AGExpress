<?php
/*
* version 1.0
* Last Update: 20/12/21
* Status: 200 OK
*/
session_start();
if( !isset($_SESSION['userName'])
    || !isset($_POST['userName'])
    || !is_string($_POST['userName'])
    || $_POST['userName']==''
) echo '<script>window.location ="/";</script>';
else{
    include_once("connection.php");
    $sqlPermiss = mysqli_prepare($connect, "DELETE FROM ageuserpermiss WHERE `AGEUser` != ( SELECT a.AGEUserName FROM ageuser a JOIN ageusertype b ON a.AGEUserType = b.AGEUserTypeID WHERE b.AGEUserTypeName = 'Admin' ) AND `AGEUser` = '".$_POST['userName']."'");
    $sqlQuery   = mysqli_prepare($connect, "DELETE FROM `ageuser` WHERE `ageuser`.`AGEUserName` = '".$_POST["userName"]."'");
    if(mysqli_stmt_execute($sqlPermiss) && mysqli_stmt_execute($sqlQuery)){
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
    }else{
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

?>