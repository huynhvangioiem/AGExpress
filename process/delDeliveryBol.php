<?php
  session_start();
  if(!isset($_SESSION['userName']) || !isset($_POST['deliveryID']) || !isset($_POST['bolID']) || !isset($_POST['action']) || !is_numeric($_POST['deliveryID']) || !is_numeric($_POST['bolID']) || $_POST['action'] !="delDeliveryBol" ) echo '<script>window.location ="/";</script>';
  else{
    include_once("connection.php");
    $sqlQuery1 = mysqli_prepare($connect, "DELETE FROM `agedeliverydetail` WHERE `AGEDelivery` = '".$_POST['deliveryID']."' AND `AGEBol` = '".$_POST['bolID']."'");
    if(mysqli_stmt_execute($sqlQuery1)){
      echo "
        <script>
            $(document).ready(() => {
                toast({
                    title: 'Thành Công!',
                    message: 'Đã xóa đơn hàng khỏi danh sách phát.',
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
                    message: 'Có lỗi rồi.',
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