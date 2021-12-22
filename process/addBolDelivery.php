<?php
  session_start();
  if( !isset($_SESSION['userName']) || !isset($_POST['deliveryID']) || !isset($_POST['bolID']) || !is_numeric($_POST['deliveryID']) ) echo '<script>window.location ="/";</script>';
  else{
    include_once("connection.php");
    $get = mysqli_query($connect, "SELECT * FROM `agebol` WHERE `AGEBoLID` = '".$_POST['bolID']."'");
    $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
    if(mysqli_num_rows($get)==1 && $data['AGEBoLStatus']==$data['AGEBoLEndPoint']){
      $sqlQuery = mysqli_prepare($connect, "INSERT INTO `agedeliverydetail`(`AGEDelivery`, `AGEBol`) VALUES ('".$_POST['deliveryID']."','".$_POST['bolID']."')");
      if(mysqli_stmt_execute($sqlQuery)){
        echo "
          <script>
            $(document).ready(() => {
              toast({
                title: 'Thành Công!',
                message: 'Đã thêm đơn hàng thành công.',
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
                title: 'Thất bại!',
                message: 'Lỗi rồi.',
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
              title: 'Thất bại!',
              message: 'Đã có lỗi xảy ra. Vui lòng kiểm tra lại!',
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