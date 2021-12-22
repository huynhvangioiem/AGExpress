<?php
/*
* version 1.0
* Last Update: 20/12/21
* Status: 200 OK
*/
  session_start();
  if( // check login and input data
    !isset($_SESSION['userName']) 
    || !isset($_POST['userName']) 
    || !isset($_POST['fullName']) 
    || !isset($_POST['userType']) 
    || !isset($_POST['userPlace']) 
    || !isset($_POST['permiss'])

    || !is_string($_POST['userName'])
    || !is_string($_POST['fullName'])
    || !is_numeric($_POST['userType'])
    || !is_numeric($_POST['userPlace'])
    || !is_array($_POST['permiss'])

    || $_POST['userName']  == ''
    || $_POST['fullName']  == ''
    || $_POST['permiss']  == []
  ) echo '<script>window.location ="/";</script>';
  else{
    include_once("connection.php");
    $permiss = [0, 0, 0, 0, 0, 0];
    foreach ($_POST['permiss'] as $key => $value) {
      $permiss[$value - 1] = 1;
    }
    $sqlQuery = mysqli_prepare($connect, "UPDATE `ageuser` SET `AGEUserFullName`='".$_POST['fullName']."',`AGEUserType`= ".$_POST['userType'].",`AGEPlace`=".$_POST['userPlace']." WHERE `AGEUserName` = '".$_POST['userName']."'");
    $sqlPermiss = mysqli_prepare($connect, "UPDATE `ageuserpermiss` SET `AGEUserPermissUser`=$permiss[0],`AGEUserPermissBol`=$permiss[1],`AGEUserPermissExport`=$permiss[2],`AGEUserPermissImport`=$permiss[3],`AGEUserPermissShipment`=$permiss[4],`AGEUserPermissReport`=$permiss[5] WHERE `AGEUser` = '".$_POST['userName']."'");
    if(mysqli_stmt_execute($sqlQuery) && mysqli_stmt_execute($sqlPermiss)){
      echo "
        <script>
          $(document).ready(() => {
            toast({
              title: 'Thành Công!',
              message: 'Tài khoản \"" . $_POST['userName'] . "\" đã được cập nhật.',
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
              message: 'Đã có lỗi. Vui lòng kiểm tra lại.',
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