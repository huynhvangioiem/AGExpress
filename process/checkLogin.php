<?php
    session_start();
    if (isset($_SESSION['userName'])) echo "true";
    if (isset($_SESSION['onlySetPass'])) echo "onlySetPass";
?>