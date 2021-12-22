<?php
	/*
		* version 1.0
		* Last Update: 20/12/21
		* Status: 200 OK
	*/
	session_start();
  if(  //check login and input data
    !isset($_SESSION['userName'])
    || !isset($_POST['bolID']) 
    || !isset($_POST['senderTel']) 
    || !isset($_POST['senderName'])
    || !isset($_POST['senderAddress'])
    || !isset($_POST['receiverTel'])
    || !isset($_POST['receiverName'])
    || !isset($_POST['receiverAddress'])
    || !isset($_POST['weight'])
    || !isset($_POST['collection'])
    || !isset($_POST['deliveryForm'])
    || !isset($_POST['transportFee'])
    || !isset($_POST['payer'])

    || !is_string($_POST['senderTel'])
    || !is_string($_POST['senderName'])
    || !is_string($_POST['senderAddress'])
    || !is_string($_POST['receiverTel'])
    || !is_string($_POST['receiverName'])
    || !is_string($_POST['receiverAddress'])

    ||$_POST['senderTel'] == ""
    ||$_POST['senderName'] == ""
    ||$_POST['senderAddress'] == ""
    ||$_POST['receiverTel'] == ""
    ||$_POST['receiverName'] == ""
    ||$_POST['receiverAddress'] == ""
  ) echo '<script>window.location ="/";</script>';
  else{
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
?>