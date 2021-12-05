<?php
    if(isset($_POST['action'])){
        isset($_POST['val']) ? $val = $_POST['val'] : $val = -1;
        include_once("connection.php");
        $res = '';
        try {
            $get = mysqli_query($connect, "SELECT * FROM `ageusertype`") or die(mysqli_connect_error($connect));
            while($data=mysqli_fetch_array($get, MYSQLI_ASSOC)){
                $val === $data['AGEUserTypeID'] ? $selected = "selected" : $selected = "";
                $res .='
                    <option value="'.$data['AGEUserTypeID'].'" '.$selected.'>Tài khoản: '.$data['AGEUserTypeName'].'</option>
                ';
            }
            echo '<option value="-1">Vui lòng chọn loại tài khoản!</option>'.$res;
        } catch (\Throwable $th) {
            
        }
    }
?>
