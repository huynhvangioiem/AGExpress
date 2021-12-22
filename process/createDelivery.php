<?php 
  session_start();
  include_once("connection.php");
  if(!isset($_POST['action']) || $_POST['action'] != "createDelivery") echo '<script>window.location ="/";</script>';
  else{
    $sqlQuery = mysqli_prepare($connect, "INSERT INTO `agedelivery`(`AGEDeliveryID`, `AGEUser`, AGEDeliveryStatus) VALUES ('".setID($connect)."','".$_SESSION['userName']."',0)");
    if(mysqli_stmt_execute($sqlQuery)){
      echo "
				<script>
					$(document).ready(() => {
						toast({
							title: 'Thành Công!',
							message: 'Đã tạo danh sách phát thành công',
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
  }
  //function setID 
	function setID($connect){
		$get = mysqli_query($connect, "SELECT COUNT(*) as STT FROM `agedelivery` WHERE `AGEDeliveryID` LIKE '%".date("y").date("m").date("d")."'") or die(mysqli_connect_error($connect));
		$data=mysqli_fetch_array($get, MYSQLI_ASSOC);
		$ID = ($data['STT']+1).date("y").date("m").date("d");
		return $ID;
	}
?>
