<?php
  //check error
  $error = false; //don't have error
  include_once("connection.php");

  // 1. input data
  if( 
    !isset($_POST['exportID']) ||
    !isset($_POST['bolID']) || 
    !is_numeric( $_POST['bolID']) ||
    !is_numeric( $_POST['exportID'])
  ) $error = true;
  
  // // 2.check bol is exported
  // try {
  //   $get = mysqli_query($connect, "SELECT * FROM agebol  WHERE `AGEBoLID` = '".$_POST['bolID']."' HAVING `AGEBoLStatus` IN (SELECT `AGEExport` FROM `ageexportdetail`)") or die(mysqli_connect_error($connect));
  //   if(mysqli_num_rows($get) != 0){
  //     $error = true;
  //     toastMsg("Đơn hàng đã được xuất. Vui lòng kiểm tra lại.",$error);
  //   }
  // } catch (\Throwable $th) {
  //   $error = true;
  //   toastMsg("Lỗi rồi! Thử lại nhé...!",$error);
  // }

  // 3. check place from and to of bol
  try {
    $getBolStt = mysqli_query($connect, "SELECT AGEBoLStatus FROM `agebol` WHERE `AGEBoLID` = '".$_POST['bolID']."'") or die(mysqli_connect_error($connect));
    $dataStt=mysqli_fetch_array($getBolStt, MYSQLI_ASSOC);
    $getExport = mysqli_query($connect, "SELECT b.AGEPlace FROM `ageexport` a JOIN ageuser b ON a.AGEUser = b.AGEUserName WHERE `AGEExportID` = '".$_POST['exportID']."'") or die(mysqli_connect_error($connect));
    $dataExport=mysqli_fetch_array($getExport, MYSQLI_ASSOC);
    if(!isset($dataStt['AGEBoLStatus']) || $dataStt['AGEBoLStatus']!=$dataExport['AGEPlace']){
      $error = true;
      toastMsg("Đơn hàng không tồn tại trong kho. Vui lòng kiểm tra lại.",$error);
    }
  } catch (\Throwable $th) {
    $error = true;
    toastMsg("Lỗi rồi! Thử lại nhé...!",$error);
  }
  // if not error
  if(!$error){
    try {
      mysqli_query($connect, "INSERT INTO `ageexportdetail`(`AGEExport`, `AGEBoL`) VALUES ('".$_POST['exportID']."','".$_POST['bolID']."')") or die(mysqli_connect_error($connect));
      mysqli_query($connect, "UPDATE `agebol` SET `AGEBoLStatus` = '".$_POST['exportID']."' WHERE `agebol`.`AGEBoLID` = '".$_POST['bolID']."'") or die(mysqli_connect_error($connect));
      toastMsg("Đã thêm thành công!",$error);
    } catch (\Throwable $th) {
      $error = true;
      toastMsg("Lỗi rồi! Thử lại nhé...!",$error);
    }
  }
  
  function toastMsg($msg, $type){
    if($type){//$type = true => error = true;
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
    }else{
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