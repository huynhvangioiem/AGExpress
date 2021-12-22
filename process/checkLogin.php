<?php
    /*
     * version 1.0
     * Last Update: 20/12/21
     * Status: 200 OK
    */
    session_start();
    // if is set userName => permiss all echo true
    if (isset($_SESSION['userName'])) echo "true";
    // if is set onlySetPass => only permiss for setpass
    if (isset($_SESSION['onlySetPass'])) echo "onlySetPass";
?>