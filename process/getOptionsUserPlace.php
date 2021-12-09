<?php
    if(isset($_POST['action'])){
        isset($_POST['val']) ? $val = $_POST['val'] : $val = -1;
        include_once("connection.php");
        $res = '';
        $message = isset($_POST['message']) ? $_POST['message'] : "Vui lòng chọn nơi làm việc";
        try {
            $get = mysqli_query($connect, "SELECT * FROM `ageplace`") or die(mysqli_connect_error($connect));
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){
                $val === $data['AGEPlaceID'] ? $selected = "selected" : $selected = "";
                $res .='<option value="'.$data['AGEPlaceID'].'" '.$selected.'>'.$data['AGEPlaceName'].'</option>';
            }
            echo '<option value="-1">'.$message.'</option>'.$res;
        } catch (\Throwable $th) {
            
        }
    }
?>
