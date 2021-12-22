<?php
	/*
		* version 1.0
		* Last Update: 20/12/21
		* Status: 200 OK
	*/
	session_start();
	if( // check login and input data
		!isset($_SESSION['userName'])
		
		|| !isset($_POST['senderTel'])
		|| !isset($_POST['senderName'])
		|| !isset($_POST['senderAddress'])
		|| !isset($_POST['receiverTel'])
		|| !isset($_POST['receiverName'])
		|| !isset($_POST['receiverAddress'])
		|| !isset($_POST['description'])
		|| !isset($_POST['weight'])
		|| !isset($_POST['collection'])
		|| !isset($_POST['deliveryForm'])
		|| !isset($_POST['transportFee'])
		|| !isset($_POST['payer'])
		|| !isset($_POST['endPoint'])
		|| !isset($_POST['deliveryWay'])

		|| !is_string($_POST['senderTel'])
		|| !is_string($_POST['senderName'])
		|| !is_string($_POST['senderAddress'])
		|| !is_string($_POST['receiverTel'])
		|| !is_string($_POST['receiverName'])
		|| !is_string($_POST['receiverAddress'])

		|| $_POST['senderTel'] ==""
		|| $_POST['senderName'] ==""
		|| $_POST['senderAddress'] ==""
		|| $_POST['receiverTel'] ==""
		|| $_POST['receiverName'] ==""
		|| $_POST['receiverAddress'] ==""

	) echo '<script>window.location ="/";</script>';
	else{ //all right => process
		include_once("connection.php");
		$IDBol = setID($connect);
		$getPlace = mysqli_query($connect, "SELECT AGEPlace FROM `ageuser` WHERE `AGEUserName` = '".$_SESSION['userName']."'") or die(mysqli_connect_error($connect));
		$dataPlace = mysqli_fetch_array($getPlace, MYSQLI_ASSOC);

		$sqlQuery = mysqli_prepare($connect, "INSERT INTO `agebol` (
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
			)
		");
		$sqlHistory = mysqli_prepare($connect, "INSERT INTO `agehistory`( `AGEBol`, `AGEHistoryTime`, `AGEHistoryStatus`) VALUES ('$IDBol','".date('Y-m-d H:i')."',".$dataPlace['AGEPlace'].")");
		if(mysqli_stmt_execute($sqlQuery) && mysqli_stmt_execute($sqlHistory)){
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
	}

	//function setID 
	function setID($connect){
		$get = mysqli_query($connect, "SELECT COUNT(*) as STT FROM `agebol` WHERE `AGEBoLID` LIKE '".date("y").date("m").date("d")."%'") or die(mysqli_connect_error($connect));
		$data=mysqli_fetch_array($get, MYSQLI_ASSOC);
		$ID = date("y").date("m").date("d").$data['STT']+1;
		return $ID;
	}
?>