<?php
  if( isset($_POST['userName']) && isset($_POST['fullName']) && isset($_POST['userType']) && isset($_POST['userPlace']) && isset($_POST['permiss'])){ //check is set data
    if( //check input value
      is_string($_POST['userName'])   && $_POST['userName']   != '' &&
      is_string($_POST['fullName'])   && $_POST['fullName']   != '' &&
      is_numeric($_POST['userType'])  && $_POST['userType']   != '' &&
      is_numeric($_POST['userPlace']) && $_POST['userPlace']  != '' &&
      is_array($_POST['permiss']) && $_POST['permiss']  != []
    ){
      include_once("connection.php");
      try { //
        mysqli_query($connect, "UPDATE `ageuser` SET `AGEUserFullName`='".$_POST['fullName']."',`AGEUserType`= ".$_POST['userType'].",`AGEPlace`=".$_POST['userPlace']." WHERE `AGEUserName` = '".$_POST['userName']."'") or die(mysqli_connect_error($connect));
        echo "
          <script>
            $(document).ready(() => {
              toast({
                title: 'Thành Công!',
                message: 'Tài khoản \"" . $_POST['userName'] . "\" đã được đăng ký thành công.',
                style: 'success-outline',
                duration: 5000,
                iconType: 'success',
              });
            })
          </script> 
        "; 
      } catch (\Throwable $th) {
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
  }
?>