<?php
  session_start();
  $error = false;
  if( !isset($_POST['exportID']) || !isset($_POST['action']) || !is_numeric($_POST['exportID']) || $_POST['action'] != "importBol"){
    $error = true;
  }
  if(!$error){// not error
    include_once("connection.php");
    //inser data to import
    $sqlQuery = mysqli_prepare($connect, "INSERT INTO `ageimport`(`AGEImportTime`, `AGEExport`, `AGEUser`) VALUES ('".date("Y-m-d H:i")."','".$_POST['exportID']."','".$_SESSION['userName']."')");
    $sqlQuery2 = mysqli_prepare($connect,"UPDATE `ageexport` SET `AGEExportStatus` = 2 WHERE `ageexport`.`AGEExportID` = '".$_POST['exportID']."'");
    if (!mysqli_stmt_execute($sqlQuery) || !mysqli_stmt_execute($sqlQuery2)) { // try query and if have error
      $error = true;
    }else{ //else not error

      $get = mysqli_query($connect, "SELECT `AGEBoL` FROM `ageexportdetail` WHERE `AGEExport` = '".$_POST['exportID']."'") or die(mysqli_connect_error($connect));
      $getPlace = mysqli_query($connect, "SELECT `AGEPlace` FROM `ageuser` WHERE `AGEUserName` ='".$_SESSION['userName']."'") or die(mysqli_connect_error($connect));
      $dataPlace = mysqli_fetch_array($getPlace, MYSQLI_ASSOC);

      while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ //browse through each data
        $sqlQuery3 = mysqli_prepare($connect,"UPDATE `agebol` SET `AGEBoLStatus` = ".$dataPlace['AGEPlace']." WHERE `AGEBoLID` = '".$data['AGEBoL']."'");
        $sqlHistory = mysqli_prepare($connect, "INSERT INTO `agehistory`( `AGEBol`, `AGEHistoryTime`, `AGEHistoryStatus`) VALUES ('".$data['AGEBoL']."','".date('Y-m-d H:i')."',".$dataPlace['AGEPlace'].")");
        if (!mysqli_stmt_execute($sqlQuery3) || !mysqli_stmt_execute($sqlHistory)) { // try query and if have error
          $error = true;
        }
      }
    }
  }

  if($error){
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
  }else{
    echo "
      <script>
        $(document).ready(() => {
          toast({
            title: 'Thành Công!',
            message: 'Đã nhập hàng thành công',
            style: 'success-outline',
            duration: 5000,
            iconType: 'success',
          });
        })
      </script> 
    ";
  }

?>