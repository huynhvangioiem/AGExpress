<?php
  if( 
    isset($_POST['bolID']) && 
    isset($_POST['senderTel']) && 
    isset($_POST['senderName']) &&
    isset($_POST['senderAddress']) &&
    isset($_POST['receiverTel']) &&
    isset($_POST['receiverName']) &&
    isset($_POST['receiverAddress']) &&
    isset($_POST['weight']) &&
    isset($_POST['collection']) &&
    isset($_POST['deliveryForm']) &&
    isset($_POST['transportFee']) &&
    isset($_POST['payer'])
  ){ //check is set data
    if( //check input value
      is_string($_POST['senderTel']) && $_POST['senderTel'] !="" && 
			is_string($_POST['senderName']) && $_POST['senderName'] !="" && 
			is_string($_POST['senderAddress']) && $_POST['senderAddress'] !="" && 
			is_string($_POST['receiverTel']) && $_POST['receiverTel'] !="" && 
			is_string($_POST['receiverName']) && $_POST['receiverName'] !="" && 
			is_string($_POST['receiverAddress']) && $_POST['receiverAddress'] !=""
    ){
      include_once("connection.php");
      try { //
        mysqli_query($connect, "UPDATE `agebol` SET `AGEBoLSenderName`='".$_POST['senderName']."',`AGEBoLSenderSdt`='".$_POST['senderTel']."',`AGEBoLSenderAddress`='".$_POST['senderAddress']."',`AGEBoLReceiverName`='".$_POST['receiverName']."',`AGEBoLReceiverSdt`='".$_POST['receiverTel']."',`AGEBoLReceiverAddress`='".$_POST['receiverAddress']."',`AGEBoLDeliveryForm`='".$_POST['deliveryForm']."',`AGEBoLTransportFee`='".$_POST['transportFee']."',`AGEBoLCollection`='".$_POST['collection']."',`AGEBoLPayer`='".$_POST['payer']."',`AGEBoLDecs`='".$_POST['description']."',`AGEBoLWeight`='".$_POST['weight']."',`AGEBoLEndPoint`='".$_POST['endPoint']."',`AGEBoLDeliveryWay`='".$_POST['deliveryWay']."' WHERE `AGEBoLID` = '".$_POST['bolID']."'") or die(mysqli_connect_error($connect));
        echo "
          <script>
            $(document).ready(() => {
              toast({
                title: 'Thành Công!',
                message: 'Thông tin đơn hàng đã được cập nhật.',
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