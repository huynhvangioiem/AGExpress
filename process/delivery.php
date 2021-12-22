<?php
  session_start();
  if( !isset($_SESSION['userName']) || !isset($_POST['deliveryID']) || !isset($_POST['action']) || !is_numeric($_POST['deliveryID']) || $_POST['action'] != "delivery") echo '<script>window.location ="/";</script>';
  else{
    include_once("connection.php");
    $sqlQuery = mysqli_prepare($connect, "UPDATE `agedelivery` SET `AGEDeliveryStatus` = '1' WHERE `agedelivery`.`AGEDeliveryID` = '".$_POST['deliveryID']."'");
    if(mysqli_stmt_execute($sqlQuery)){
      echo "
        <script>
            $(document).ready(() => {
                toast({
                    title: 'Thành Công!',
                    message: 'Danh sách các đơn hàng cần phát đã được tạo.',
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
                    message: 'Có lỗi xảy ra. Vui lòng kiểm tra lại.',
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