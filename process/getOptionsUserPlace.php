<?php
    if(isset($_POST['action'])){ //check is set data
        isset($_POST['val']) ? $val = $_POST['val'] : $val = -1; //set value to selected
        include_once("connection.php");
        $res = ''; 
        $message = isset($_POST['message']) ? $_POST['message'] : "Vui lòng chọn nơi làm việc"; // set message
        try { // try to get place
            $get = mysqli_query($connect, "SELECT * FROM `ageplace`") or die(mysqli_connect_error($connect));
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ // browse through each data and
                $val === $data['AGEPlaceID'] ? $selected = "selected" : $selected = "";
                $res .='<option value="'.$data['AGEPlaceID'].'" '.$selected.'>'.$data['AGEPlaceName'].'</option>';
            }
            echo '<option value="-1">'.$message.'</option>'.$res;
        } catch (\Throwable $th) {
            
        }
    }
?>
