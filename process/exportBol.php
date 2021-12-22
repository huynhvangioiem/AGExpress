<?php 
  /*
		* version 1.0
		* Last Update: 20/12/21
		* Status: 200 OK
	*/
	session_start();
	if( !isset($_SESSION['userName']) || !isset( $_POST['exportID']) || !isset($_POST['action']) || $_POST['action']!="exportBol" || !is_numeric($_POST['exportID']) ) 
    echo '<script>window.location ="/";</script>';
	else{ //all right => process
    include_once("connection.php");
    $get = mysqli_query($connect, "SELECT * FROM `ageexportdetail` WHERE `AGEExport` = '".$_POST['exportID']."'") or die(mysqli_connect_error($connect));
    if(mysqli_num_rows($get) != 0 ){
      $sqlQuery = mysqli_prepare($connect, "UPDATE `ageexport` SET `AGEExportStatus` = 1, `AGEExportTime` = '".date("Y-m-d H:i")."' WHERE `AGEExportStatus` = 0 AND `ageexport`.`AGEExportID` = '".$_POST['exportID']."'");
      if(mysqli_stmt_execute($sqlQuery)){
        echo "
          <script>
            $(document).ready(() => {
              toast({
                title: 'Thành Công!',
                message: 'Đã xuất kho thành công.',
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
    }else{
      echo "
        <script>
          $(document).ready(() => {
            toast({
              title: 'Thất Bại!',
              message: 'Không có đơn hàng nào để xuất. Vui lòng kiểm tra lại!',
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