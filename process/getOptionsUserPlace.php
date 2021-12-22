<?php
    /*
    * version 1.0
    * last Update: 20/12/21
    * start: 200 OK
    */
    session_start();
    //check login
    if( !isset($_SESSION['userName']) || !isset($_POST['action']) ) { // check login and input data
        echo '<script>window.location ="/";</script>';
    }else {// all right => process
        $val = isset($_POST['val']) ? $_POST['val'] : $val = -1; //set value to selected
        $message = isset($_POST['message']) ? $_POST['message'] : "Vui lòng chọn nơi làm việc"; // set message
        include_once("connection.php");
        $res = ''; 
        $get = mysqli_query($connect, "SELECT * FROM `ageplace`") or die(mysqli_connect_error($connect));
        while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){ // browse through each data and
            $val === $data['AGEPlaceID'] ? $selected = "selected" : $selected = "";
            $res .='<option value="'.$data['AGEPlaceID'].'" '.$selected.'>'.$data['AGEPlaceName'].'</option>';
        }
        echo '<option value="-1">'.$message.'</option>'.$res;
    }
?>
