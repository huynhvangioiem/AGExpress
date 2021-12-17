<?php 
  if( isset( $_POST['exportID']) && isset($_POST['action'])){
    if($_POST['action']=="exportBol" && is_numeric($_POST['exportID'])){
      try {
        include_once("connection.php");
        mysqli_query($connect, "UPDATE `ageexport` SET `AGEExportStatus` = 1, `AGEExportTime` = '".date("Y-m-d H:i")."' WHERE `AGEExportStatus` = 0 AND `ageexport`.`AGEExportID` = '".$_POST['exportID']."'") or die(mysqli_connect_error($connect));
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