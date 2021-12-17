<?php
	session_start();
  if( isset($_POST['timeExport']) && isset($_POST['shipment']) && isset($_POST['destination'])){ //check isset data
    if( //check input value
      is_string($_POST['timeExport'])&& $_POST['timeExport']!='' && 
      is_numeric($_POST['shipment'])&& $_POST['shipment']!='' && 
      is_numeric($_POST['destination'])&& $_POST['destination']!=''
    ){
      try { // try to create a export and aler if success
        include_once("connection.php");
			  $IDExport = setID($connect);
        mysqli_query($connect, "INSERT INTO `ageexport`(`AGEExportID`, `AGEExportTime`, `AGEShipment`, `AGEDestination`, `AGEUser`, `AGEExportStatus`) 
				VALUES ('".$IDExport."','".$_POST['timeExport']."','".$_POST['shipment']."','".$_POST['destination']."','".$_SESSION['userName']."',0)") or die(mysqli_error($connect));
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
		$get = mysqli_query($connect, "SELECT COUNT(*) as STT FROM `ageexport` WHERE `AGEExportID` LIKE '".date("d").date("m").date("y")."%'") or die(mysqli_connect_error($connect));
		$data=mysqli_fetch_array($get, MYSQLI_ASSOC);
		$ID = date("d").date("m").date("y").$data['STT']+1;
		return $ID;
	}
?>