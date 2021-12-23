<?php
	session_start();
	if(
		!isset($_SESSION['userName']) 
		|| !isset($_POST['timeExport']) 
		|| !isset($_POST['shipment']) 
		|| !isset($_POST['destination']) 

		|| !is_string($_POST['timeExport'])
		|| !is_numeric($_POST['shipment'])
		|| !is_numeric($_POST['destination'])
	) echo '<script>window.location ="/";</script>';
	else{
		include_once("connection.php");
		$IDExport = setID($connect);
		$get = mysqli_query($connect, "SELECT * FROM `ageuser` WHERE `AGEUserName` = '".$_SESSION['userName']."'");
		$data = mysqli_fetch_array($get, MYSQLI_ASSOC);
		if($data['AGEPlace']!=$_POST['destination']){
			$sqlQuery = mysqli_prepare($connect, "INSERT INTO `ageexport`(`AGEExportID`, `AGEExportTime`, `AGEShipment`, `AGEDestination`, `AGEUser`, `AGEExportStatus`) 
			VALUES ('".$IDExport."','".$_POST['timeExport']."','".$_POST['shipment']."','".$_POST['destination']."','".$_SESSION['userName']."',0)");
			if(mysqli_stmt_execute($sqlQuery)){
				echo "
					<script>
						$(document).ready(() => {
							toast({
								title: 'Thành Công!',
								message: 'Đã tạo phiếu xuất thành công.',
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
								message: 'Nơi nhận trùng với nơi bạn đang làm việc.',
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
		$get = mysqli_query($connect, "SELECT COUNT(*) as STT FROM `ageexport` WHERE `AGEExportID` LIKE '".date("d").date("m").date("y")."%'") or die(mysqli_connect_error($connect));
		$data=mysqli_fetch_array($get, MYSQLI_ASSOC);
		$ID = date("d").date("m").date("y").$data['STT']+1;
		return $ID;
	}
?>