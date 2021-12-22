<?php
  $error = false;
  include_once("connection.php");
  if( 
    !isset($_POST['exportID']) ||
    !isset($_POST['bolID']) ||
    !isset($_POST['action']) ||
    !is_numeric($_POST['exportID']) ||
    !is_numeric($_POST['bolID']) ||
    $_POST['action'] != "delBolOnExport" 
  ) $error = true;

  if(!$error){
    try {
      mysqli_query($connect, "DELETE FROM `ageexportdetail` WHERE `ageexportdetail`.`AGEBoL` = '".$_POST['bolID']."' AND `ageexportdetail`.`AGEExport` = '".$_POST['exportID']."'") or die(mysqli_connect_error($connect));
      $getPlace = mysqli_query($connect, "SELECT b.AGEPlace FROM `ageexport` a JOIN ageuser b ON a.AGEUser = b.AGEUserName WHERE `AGEExportID` = '".$_POST['exportID']."'") or die(mysqli_connect_error($connect));
      $dataPlace = mysqli_fetch_array($getPlace, MYSQLI_ASSOC);
      mysqli_query($connect, "UPDATE `agebol` SET `AGEBoLStatus` = '".$dataPlace['AGEPlace']."' WHERE `agebol`.`AGEBoLID` = '".$_POST['bolID']."'") or die(mysqli_connect_error($connect));
      $sqlHistory = mysqli_prepare($connect, "DELETE FROM `agehistory` WHERE `AGEBol` = '".$_POST['bolID']."' AND `AGEHistoryStatus` = '".$_POST['exportID']."'");
      mysqli_stmt_execute($sqlHistory);
      toastMsg("Đã xóa đơn hành khỏi phiếu xuất!",$error);
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