<?php
  session_start();
  if(!isset($_SESSION['userName']) || !isset($_POST['bolID']) || !is_numeric($_POST['bolID']) || !isset($_POST['action'])) echo '<script>window.location ="/";</script>';
  else{
    include_once("connection.php");
    if($_POST['action'] == 'success'){
      $sqlQuery = mysqli_prepare($connect, "UPDATE `agebol` SET `AGEBoLStatus` = '200' WHERE `agebol`.`AGEBoLID` = '".$_POST['bolID']."'");
      $sqlHistory = mysqli_prepare($connect, "INSERT INTO `agehistory`( `AGEBol`, `AGEHistoryTime`, `AGEHistoryStatus`) VALUES ('".$_POST['bolID']."','".date('Y-m-d H:i')."',200)");
      if(mysqli_stmt_execute($sqlQuery)&& mysqli_stmt_execute($sqlHistory)){
        echo "
          <script>
              $(document).ready(() => {
                  toast({
                      title: 'Thành Công!',
                      message: 'Đơn hàng đã phát thành công.',
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
  }
?>