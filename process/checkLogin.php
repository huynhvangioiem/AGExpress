<?php
    session_start();
    if (isset($_SESSION['userName'])) echo "true";
?>