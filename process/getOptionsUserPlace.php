<?php
    if(isset($_POST['action'])){
        isset($_POST['val']) ? $val = $_POST['val'] : $val = -1;
        include_once("connection.php");
        $res = '';
        try {
            $get = mysqli_query($connect, "SELECT * FROM `ageplace`") or die(mysqli_connect_error($connect));
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){
                $val === $data['AGEPlaceID'] ? $selected = "selected" : $selected = "";
                $res .='<option value="'.$data['AGEPlaceID'].'" '.$selected.'>Điểm giao dịch: '.$data['AGEPlaceName'].'</option>';
            }
            echo '<option value="-1">Vui lòng chọn loại nơi làm việc!</option>'.$res;
        } catch (\Throwable $th) {
            
        }
    }
?>
