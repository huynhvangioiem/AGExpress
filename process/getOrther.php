<?php
/*
 * version 1.0
 * last Update: 20/12/21
 * start: 200 OK
 */
session_start();
if( (!isset($_SESSION['userName']) && !isset($_SESSION["onlySetPass"])) || !isset($_POST['funcName']) ){ //check login and input data
    echo '<script>window.location ="/";</script>';
}else {  //else all right
    if($_POST['funcName']==="getSession") getSession();
    if($_POST['funcName']==="getPermissions" && isset($_POST['userName']) ) getPermissions($_POST['userName']);
}
//getgetPermissions
function getPermissions($userName){
    include_once("connection.php");
    $get = mysqli_query($connect, "SELECT * FROM `ageuserpermiss` WHERE `AGEUser` = '$userName'") or die(mysqli_connect_error($connect));
    $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
    $permiss = new stdClass();
    $permiss->permiss1 = $data['AGEUserPermissUser'];
    $permiss->permiss2 = $data['AGEUserPermissBol'];
    $permiss->permiss3 = $data['AGEUserPermissExport'];
    $permiss->permiss4 = $data['AGEUserPermissImport'];
    $permiss->permiss5 = $data['AGEUserPermissShipment'];
    $permiss->permiss6 = $data['AGEUserPermissReport'];
    $permiss = json_encode($permiss);
    echo $permiss;
}

// getSession
function getSession(){
    if(isset($_SESSION["onlySetPass"])) echo $_SESSION["onlySetPass"];
    else echo $_SESSION["userName"];
}
?>