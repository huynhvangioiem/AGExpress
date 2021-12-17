<?php
    if(isset($_POST['action'])){ //check is set data
        isset($_POST['val']) ? $val = $_POST['val'] : $val = -1; //set value to selected
        include_once("connection.php");
        $res = ''; 
        $message = isset($_POST['message']) ? $_POST['message'] : "Vui lòng chọn chuyến xe"; // set message
        try { // try to get place
            $get = mysqli_query($connect, "SELECT a.*, b.AGEPlaceName as placeFrom, c.AGEPlaceName as placeTo FROM `ageshipment` a JOIN ageplace b ON a.`AGEShipmentFrom` = b.AGEPlaceID JOIN ageplace c ON a.`AGEShipmentTo` = c.AGEPlaceID WHERE `AGEShipmentStatus` = 1 OR `AGEShipmentStatus` = 0") or die(mysqli_connect_error($connect));
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ // browse through each data and
                $val === $data['AGEShipmentID'] ? $selected = "selected" : $selected = "";
                $res .='<option value="'.$data['AGEShipmentID'].'" '.$selected.'>'.$data['AGEShipmentID'].'  |  '.$data['AGEShipmentDriverName'].'  |  '.$data['placeFrom'].' - '.$data['placeTo'].'  |  '.date_format(date_create($data['AGEShipmentStart']),"d/m/Y H:i").' - '.date_format(date_create($data['AGEShipmentEnd']),"d/m/Y H:i").'</option>';
            }
            echo '<option value="-1">'.$message.'</option>'.$res;
        } catch (\Throwable $th) {
            
        }
    }
?>
