<?php
    /*
    * version 1.0
    * last Update: 20/12/21
    * start: 200 OK
    */
    session_start();
    if( !isset($_SESSION['userName']) || !isset($_POST['userName']) || $_POST['userName']=='' || !is_string($_POST['userName']) ){ // check login and input data
        echo '<script>window.location ="/";</script>';
    }else {// all right => process
        include_once("connection.php");
        $get = mysqli_query($connect, "SELECT * FROM `ageuser` WHERE `AGEUserName` = '".$_POST['userName']."'") or die(mysqli_connect_error($connect));
        $data=mysqli_fetch_array($get, MYSQLI_ASSOC);
        echo '  
            <img class="img-responsive" src="img/Logo4.jpg" alt="">
            <ul class="profileMenu">
                <li class="account">
                    <h3>'.$data['AGEUserFullName'].'</h3>
                    <h3>'.$data['AGEUserName'].'</h3>
                </li>
                <li><a href="">Thông tin cá nhân</a></li>
                <li><a href="/account.html">Đổi mật khẩu</a></li>
                <li><a onclick="logout()">Đăng xuất</a></li>
            </ul>
        ';
    }
?>