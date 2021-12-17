<?php
	session_start();
	if( //check is set input data
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
	){ 
		if( //check input data
			is_string($_POST['senderTel']) && $_POST['senderTel'] !="" && 
			is_string($_POST['senderName']) && $_POST['senderName'] !="" && 
			is_string($_POST['senderAddress']) && $_POST['senderAddress'] !="" && 
			is_string($_POST['receiverTel']) && $_POST['receiverTel'] !="" && 
			is_string($_POST['receiverName']) && $_POST['receiverName'] !="" && 
			is_string($_POST['receiverAddress']) && $_POST['receiverAddress'] !=""
		){
			include_once("connection.php");
			//call func to set ID
			$IDBol = setID($connect);
			try { // try to insert data into database and aler if success
				$getPlace = mysqli_query($connect, "SELECT * FROM `ageuser` WHERE `AGEUserName` = '".$_SESSION['userName']."'") or die(mysqli_connect_error($connect));
				$dataPlace = mysqli_fetch_array($getPlace, MYSQLI_ASSOC);
				mysqli_query($connect, "INSERT INTO `agebol` (
					`AGEBoLID`,
					`AGEBoLSenderName`,
					`AGEBoLSenderSdt`,
					`AGEBoLSenderAddress`,
					`AGEBoLReceiverName`,
					`AGEBoLReceiverSdt`,
					`AGEBoLReceiverAddress`,
					`AGEBoLDeliveryForm`,
					`AGEBoLTransportFee`,
					`AGEBoLCollection`,
					`AGEBoLPayer`,
					`AGEBoLStatus`,
					`AGEUser`,
					`AGEBoLDecs`,
					`AGEBoLWeight`,
					`AGEBoLEndPoint`,
					`AGEBoLDeliveryWay`
				) VALUES (
					'".$IDBol."',
					'".$_POST['senderName']."',
					'".$_POST['senderTel']."',
					'".$_POST['senderAddress']."',
					'".$_POST['receiverName']."',
					'".$_POST['receiverTel']."',
					'".$_POST['receiverAddress']."',
					'".$_POST['deliveryForm']."',
					'".$_POST['transportFee']."',
					'".$_POST['collection']."',
					'".$_POST['payer']."',
					'".$dataPlace['AGEPlace']."',
					'".$_SESSION['userName']."',
					'".$_POST['description']."',
					'".$_POST['weight']."',
					'".$_POST['endPoint']."',
					'".$_POST['deliveryWay']."'
					)") or die(mysqli_connect_error($connect));
				echo "
					<script>
						$(document).ready(() => {
							toast({
								title: 'Thành Công!',
								message: 'Đã tạo vận đơn thành công',
								style: 'success-outline',
								duration: 5000,
								iconType: 'success',
							});
						})
						printer('".$IDBol."','newbol');
					</script> 
				";
			} catch (\Throwable $th) { //if wrong, aler error message
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
	//function setID 
	function setID($connect){
		$get = mysqli_query($connect, "SELECT COUNT(*) as STT FROM `agebol` WHERE `AGEBoLID` LIKE '".date("y").date("m").date("d")."%'") or die(mysqli_connect_error($connect));
		$data=mysqli_fetch_array($get, MYSQLI_ASSOC);
		$ID = date("y").date("m").date("d").$data['STT']+1;
		return $ID;
	}
?>