<?php
  if( isset($_POST['driverName']) && isset($_POST['licensePlates']) && isset($_POST['from']) && isset($_POST['to']) && isset($_POST['start']) && isset($_POST['end'])){ //check isset data
    if( //check input value
      is_string($_POST['driverName'])&& $_POST['driverName']!='' && 
      is_string($_POST['licensePlates'])&& $_POST['licensePlates'] != '' &&
      is_numeric($_POST['from'])  && $_POST['from']   !='' &&
      is_numeric($_POST['to']) && $_POST['to']   != '' &&
      is_string($_POST['start'])   && $_POST['start']   != '' &&
      is_string($_POST['end']) && $_POST['end'] != ''
    ){
      try { // try to create a shipment and aler if success
        include_once("connection.php");
			  $IDShipment = setID($connect);
        mysqli_query($connect, "INSERT INTO `ageshipment`(`AGEShipmentID`, `AGEShipmentDriverName`, `AGEShipmentFrom`, `AGEShipmentTo`, `AGEShipmentBKS`, `AGEShipmentStart`, `AGEShipmentEnd`, `AGEShipmentStatus`) 
        VALUES ('$IDShipment', '".$_POST['driverName']."', ".$_POST['from'].", ".$_POST['to'].", '".$_POST['licensePlates']."', '".$_POST['start']."', '".$_POST['end']."', 0)") or die(mysqli_error($connect));
        echo "
					<script>
						$(document).ready(() => {
							toast({
								title: 'Thành Công!',
								message: 'Chuyến hàng đã được tạo.',
								style: 'success-outline',
								duration: 5000,
								iconType: 'success',
							});
						})
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
		$get = mysqli_query($connect, "SELECT COUNT(*) as STT FROM `ageshipment` WHERE `AGEShipmentID` LIKE '%".date("d").date("m").date("y")."'") or die(mysqli_connect_error($connect));
		$data=mysqli_fetch_array($get, MYSQLI_ASSOC);
		$ID = ($data['STT']+1).date("d").date("m").date("y");
		return $ID;
	}
?>