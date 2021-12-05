<?php
session_start();
if (!isset($_SESSION['userName'])) {
    echo '<meta http-equiv="refresh" content="0;URL=./login.html" />';
}else{
?>

<!DOCTYPE html>
<html lang="vn">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGEXPRESS</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- JQUERY -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <!-- fontawesome -->

    <link rel="stylesheet" href="CSS/styles.css"> <!-- Styles -->
    <link rel="stylesheet" href="CSS/style.css"> <!-- Style -->


</head>

<body>
    <div class="container-fluid">
        <!-- Banner -->
        <div class="row banner">
            <div class="container">
                <div class="row bannerContent">
                    <div class="col-2 col-m-2 col-s-0 logo">
                        <img class="img-responsive" src="img/Logo4.jpg" alt="">
                    </div>
                    <div class="col-8 col-m-8 col-s-12 header">
                        <h1>AGEXPRESS</h1>
                        <h1>Hệ Thống Quản Lý</h1>
                    </div>
                    <div class="col-2 col-m-2 col-s-0">
                        <div class="profile">
                            <img class="img-responsive" src="img/Logo4.jpg" alt="">
                            <ul class="profileMenu">
                                <li class="account">
                                    <h3>Huỳnh Văn Giỏi Em</h3>
                                    <h3>0335687425</h3>
                                </li>
                                <li><a href="">Thông tin cá nhân</a></li>
                                <li><a href="">Đổi mật khẩu</a></li>
                                <li><a href="">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Menu -->
        <div class="row menu">
            menu
        </div>
        <!-- Content -->
        <!-- Footer -->
        <div class="row footer">
            <div class="container">
                <div class="row footerContent">
                    <div class="col-3 col-m-3 col-s-12">
                        <h3>Copyright © 2022 AGEXPRESS</h3>
                    </div>
                    <div class="col-6 col-m-6 col-s-12">
                        <h3>Công Ty TNHH AGEXPRESS</h3>
                        <h4>Số 94, Triệu Thị Trinh, Phường Bình Khánh, Long Xuyên, An Giang</h4>
                    </div>
                    <div class="col-3 col-m-3 col-s-12">
                        <h3><a href="https://huynhvangioiem.github.io/TLA_Library/">Development by TLAIT</a></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
}
?>