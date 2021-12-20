<?php
  if( isset($_POST['id']) ){ //check isset data
    if( //check input value 
      is_string($_POST['id'])   && $_POST['id']   != ''
    ){ 
      include_once("connection.php");
      try { //
        mysqli_query($connect, "UPDATE `ageshipment` SET  `AGEShipmentStatus`= 2, AGEShipmentEnd = '".date("Y-m-d H:i")."' WHERE `AGEShipmentID`='".$_POST['id']."'") or die(mysqli_connect_error($connect));
        echo "
          <script>
            $(document).ready(() => {
              toast({
                title: 'Thành Công!',
                message: 'Chuyến hàng đã xuất phát.',
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