<?php
session_start();
if(isset($_POST['funcName'])){
    if($_POST['funcName']==="getSession") getSession();
}
// getSession
function getSession(){
    if(isset($_SESSION["onlySetPass"])) echo $_SESSION["onlySetPass"];
    else echo $_SESSION["userName"];
}
?>