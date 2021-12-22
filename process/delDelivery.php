<?php
  session_start();
  if(!isset($_SESSION['userName']) || !isset($_POST['deliveryID']) || !isset($_POST['action']) || !is_numeric($_POST['deliveryID']) || $_POST['action'] !="delDelivery" ) echo '<script>window.location ="/";</script>';
  else{
    include_once("connection.php");
    $sqlQuery1 = mysqli_prepare($connect, "DELETE FROM `agedeliverydetail` WHERE `AGEDelivery` = '".$_POST['deliveryID']."'");
    $sqlQuery2 = mysqli_prepare($connect, "DELETE FROM `agedelivery` WHERE `AGEDeliveryID` = '".$_POST['deliveryID']."'");
    if(mysqli_stmt_execute($sqlQuery1) && mysqli_stmt_execute($sqlQuery2) ){
      echo "
        <script>
            $(document).ready(() => {
                toast({
                    title: 'Thành Công!',
                    message: 'Đã xóa danh sách phát thành công.',
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
                    message: 'Không thể xóa danh sách phát. Vui lòng thử lại.',
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