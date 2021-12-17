<?php
  if( isset($_POST['exportID']) && isset($_POST['timeExport']) && isset($_POST['shipment']) && isset($_POST['destination'])){ //check isset data
    if( //check input value
      is_numeric($_POST['exportID'])&& $_POST['exportID']!='' && 
      is_string($_POST['timeExport'])&& $_POST['timeExport'] != '' &&
      is_numeric($_POST['shipment'])  && $_POST['shipment']   !='' &&
      is_numeric($_POST['destination']) && $_POST['destination']   != ''
    ){
      include_once("connection.php");
      try { //
        mysqli_query($connect, "UPDATE `ageexport` SET `AGEExportTime`='".$_POST['timeExport']."',`AGEShipment`='".$_POST['shipment']."',`AGEDestination`='".$_POST['destination']."' WHERE `AGEExportID` = '".$_POST['exportID']."'") or die(mysqli_connect_error($connect));
        echo "
          <script>
            $(document).ready(() => {
              toast({
                title: 'Thành Công!',
                message: 'Thông tin phiếu xuất đã được cập nhật',
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