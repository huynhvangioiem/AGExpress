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
    || !isset($_POST['password']) 
    || !isset($_POST['fullName']) 
    || !isset($_POST['userType']) 
    || !isset($_POST['userPlace']) 
    || !isset($_POST['permiss'])
    
    || !is_string($_POST['userName'])
    || !is_string($_POST['password'])
    || !is_string($_POST['fullName'])
    || !is_numeric($_POST['userType'])
    || !is_numeric($_POST['userPlace'])
    || !is_array($_POST['permiss'])

    || $_POST['userName'] == ""
    || $_POST['password'] == ""
    || $_POST['fullName'] == ""
    || $_POST['permiss'] == []
	) echo '<script>window.location ="/";</script>';
  else { //all right => process
    $permiss = [0, 0, 0, 0, 0, 0];
    foreach ($_POST['permiss'] as $key => $value) {
      $permiss[$value - 1] = 1;
    }
    $password = md5($_POST['password']);
    include_once("connection.php");
    $sqlQuery = mysqli_prepare($connect, "INSERT INTO `ageuser`(`AGEUserName`, `AGEUserPassword`, `AGEUserFullName`, `AGEUserType`, `AGEPlace`, `AGEUserStatus`)
      VALUES ('" . $_POST['userName'] . "','$password','" . $_POST['fullName'] . "'," . $_POST['userType'] . "," . $_POST['userPlace'] . ",0)");
    $sqlQueryPermiss = mysqli_prepare($connect, "INSERT INTO `ageuserpermiss`( `AGEUser`, `AGEUserPermissUser`, `AGEUserPermissBol`, `AGEUserPermissExport`, `AGEUserPermissImport`, `AGEUserPermissShipment`, `AGEUserPermissReport`) 
      VALUES ('" . $_POST['userName'] . "',$permiss[0],$permiss[1],$permiss[2],$permiss[3],$permiss[4],$permiss[5])");    
    if (mysqli_stmt_execute($sqlQuery) && mysqli_stmt_execute($sqlQueryPermiss)) {
      toastMsg('Tài khoản '.$_POST['userName'].' đã được đăng ký thành công.',false);
    } else {
      $get = mysqli_query($connect, "SELECT * FROM `ageuser` WHERE `AGEUserName` = '" . $_POST['userName'] . "'") or die(mysqli_connect_error($connect));
      if (mysqli_num_rows($get) == 1) { //if the user is exist then aler message error
        toastMsg('Tài khoản ' . $_POST['userName'] . ' đã được đăng ký.', true);
      }
    }
  }

function toastMsg($msg, $type)
{
  if ($type) { //$type = true => error = true;
    echo "
        <script>
          $(document).ready(() => {
            toast({
              title: 'Thất Bại!',
              message: '$msg',
              style: 'danger-outline',
              duration: 5000,
              iconType: 'danger',
            });
          })
        </script> 
      ";
  } else {
    echo "
        <script>
          $(document).ready(() => {
            toast({
              title: 'Thành Công!',
              message: '$msg',
              style: 'success-outline',
              duration: 5000,
              iconType: 'success',
            });
          })
        </script> 
      ";
  }
}
?>