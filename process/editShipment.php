<?php
  if( isset($_POST['shipmentId']) && isset($_POST['driverName']) && isset($_POST['licensePlates']) && isset($_POST['from']) && isset($_POST['to']) && isset($_POST['start']) && isset($_POST['end'])){ //check isset data
    if( //check input value
      is_string($_POST['driverName'])&& $_POST['driverName']!='' && 
      is_string($_POST['licensePlates'])&& $_POST['licensePlates'] != '' &&
      is_numeric($_POST['from'])  && $_POST['from']   !='' &&
      is_numeric($_POST['to']) && $_POST['to']   != '' &&
      is_string($_POST['start'])   && $_POST['start']   != '' &&
      is_string($_POST['end']) && $_POST['end'] != ''
    ){
      include_once("connection.php");
      try { //
        mysqli_query($connect, "UPDATE `ageshipment` SET `AGEShipmentDriverName`='".$_POST['driverName']."',`AGEShipmentFrom`=".$_POST['from'].",`AGEShipmentBKS`='".$_POST['licensePlates']."',`AGEShipmentStart`='".$_POST['start']."',`AGEShipmentEnd`='".$_POST['end']."',`AGEShipmentTo`= ".$_POST['to']." WHERE `AGEShipmentID`='".$_POST['shipmentId']."'") or die(mysqli_connect_error($connect));
        echo "
          <script>
            $(document).ready(() => {
              toast({
                title: 'Thành Công!',
                message: 'Thông tin chuyến hàng đã được cập nhật',
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