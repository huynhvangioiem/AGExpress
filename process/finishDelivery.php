<?php
  session_start();
  if(!isset($_SESSION['userName']) || !isset($_POST['deliveryID'])) echo '<script>window.location ="/";</script>';
  else{
    include_once("connection.php");
    $sqlQuery = mysqli_prepare($connect, "UPDATE `agedelivery` SET `AGEDeliveryStatus` = '2' WHERE `agedelivery`.`AGEDeliveryID` = '".$_POST['deliveryID']."'");
    if(mysqli_stmt_execute($sqlQuery)){
      echo "
        <script>
            $(document).ready(() => {
                toast({
                    title: 'Thành Công!',
                    message: 'OK!.',
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
                    title: 'Thất bại!',
                    message: 'Đã có lỗi.',
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