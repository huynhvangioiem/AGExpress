<?php
/*
* version 1.0
* last Update: 20/12/21
* start: 200 OK
*/
session_start();
include("TLABarcode.php");
if( !isset($_SESSION['userName']) || !isset($_POST['action']) || !isset($_POST['id']) || $_POST['id'] =="" ){ 
    echo '<script>window.location ="/";</script>';
}else{ //all right
    include_once("connection.php");
    if ( ($_POST['action'] == "newbol") && is_numeric($_POST['id']) ) { 
        printNewbol($connect);
        printBol($connect);
    }else if (($_POST['action'] == "bol") && is_numeric($_POST['id']) ) {
        // else if action =  bol then process and return the component for bol
        printBol($connect);
    }else if ($_POST['action'] == "formbol") {
        // else if action =  formbol, return the component for formbol
        ?>
            <div class="row header">
                <div class="col-4 col-m-4 col-s-4 logo">
                    <img class="img-responsive" src="img/Logo4.jpg" alt="">
                </div>
                <div class="col-4 col-m-4 col-s-4">
                    <h2>AGEXPRESS</h2>
                    <h1>Phiếu Gửi</h1>
                </div>
                <div class="col-4 col-m-4 col-s-4 bardcode"></div>
            </div>
            <div class="row content">
                <div class="col-6 col-m-6 col-s-6 height30">
                    <div class="row">
                        <div class="col-12 col-m-12 col-s-12 title">
                            <h3>Thông Tin Gửi</h3>
                        </div>
                        <div class="col-4 col-m-4 col-s-4">Người Gửi:</div>
                        <div class="col-8 col-m-8 col-s-8">..................................................</div>
                        <div class="col-4 col-m-4 col-s-4">Điện thoại:</div>
                        <div class="col-8 col-m-8 col-s-8">..................................................</div>
                        <div class="col-4 col-m-4 col-s-4">Địa chỉ:</div>
                        <div class="col-8 col-m-8 col-s-8">..................................................</div>
                    </div>
                </div>
                <div class="col-6 col-m-6 col-s-6 height30">
                    <div class="row">
                        <div class="col-12 col-m-12 col-s-12 title">
                            <h3>Thông Tin Nhận</h3>
                        </div>
                        <div class="col-4 col-m-4 col-s-4">Người Nhận:</div>
                        <div class="col-8 col-m-8 col-s-8">..................................................</div>
                        <div class="col-4 col-m-4 col-s-4">Điện thoại:</div>
                        <div class="col-8 col-m-8 col-s-8">..................................................</div>
                        <div class="col-4 col-m-4 col-s-4">Địa chỉ:</div>
                        <div class="col-8 col-m-8 col-s-8">..................................................</div>
                    </div>
                </div>
            </div>
            <div class="row footer">
                <div class="col-2 col-m-2 col-s-2">
                    <img src="img/frame.png" alt="">
                </div>
                <div class="col-8 col-m-8 col-s-8">
                    <h4>Công Ty TNHH AGEXPRESS</h4>
                    <h5>Số 94, Triệu Thị Trinh, Phường Bình Khánh, Long Xuyên, An Giang</h5>
                    <h5>Điện thoại: 0975779603</h5>
                </div>
                <div class="col-2 col-m-2 col-s-2">
                    <h5>Develop by TLAIT</h5>
                </div>
            </div>
        <?php
    }else{
        echo "
        <script>
            window.close();
        </script>
        ";
    }
}
function printNewbol($connect){
    //check action, if action = newbol then process and return component for newbol
    $get = mysqli_query($connect, "SELECT * FROM `agebol` JOIN ageplace ON agebol.`AGEBoLEndPoint` = ageplace.AGEPlaceID WHERE `AGEBoLID` = '" . $_POST['id'] . "'") or die(mysqli_connect_error($connect));
    $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
    $bolID =  createBar128($data['AGEBoLID']);
    ?>
        <div class="row header">
            <div class="col-4 col-m-4 col-s-4 logo"></div>
            <div class="col-4 col-m-4 col-s-4"></div>
            <div class="col-4 col-m-4 col-s-4 bardcode"><?php echo $bolID ?></div>
        </div>
        <div class="row content breakPage">
            <div class="col-6 col-m-6 col-s-6 height30"></div>
            <div class="col-6 col-m-6 col-s-6 height30"></div>
            <div class="col-6 col-m-6 col-s-6">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12 title">
                        <h3>Thông Tin Hàng Hóa</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Hàng hóa:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDecs'] ?></div>
                    <div class="col-4 col-m-4 col-s-4">Trọng lượng:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLWeight'] ?> kg</div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-6">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12 title">
                        <h3>Cước Phí</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Phí vận chuyển:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLTransportFee']) ?> VNĐ</div>
                    <div class="col-4 col-m-4 col-s-4">Thu phí:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLPayer'] == 0 ? "Người gửi" : "Người nhận" ?></div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-6">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12 title">
                        <h3>Thông Tin Đơn Hàng</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Kho phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEPlaceName'] ?></div>
                    <div class="col-4 col-m-4 col-s-4">Hình thức phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDeliveryForm'] == 1 ? "Người nhận tự đến lấy" : "Phát đến địa chỉ nhận" ?></div>
                    <div class="col-4 col-m-4 col-s-4">Hướng dẫn phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDeliveryWay'] ?></div>
                    <div class="col-4 col-m-4 col-s-4">Thu hộ:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLCollection']) ?> VNĐ</div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-6 sign">
                <div class="row">
                    <div class="col-6 col-m-6 col-s-6">.........., ..../..../......</div>
                    <div class="col-6 col-m-6 col-s-6">.........., ..../..../......</div>
                    <div class="col-6 col-m-6 col-s-6">
                        <h3>Nhân Viên</h3>
                    </div>
                    <div class="col-6 col-m-6 col-s-6">
                        <h3>Người Gửi</h3>
                    </div>
                    <div class="col-6 col-m-6 col-s-6">
                        <h5>Ký và ghi rõ họ tên</h5>
                    </div>
                    <div class="col-6 col-m-6 col-s-6">
                        <h5>Ký và ghi rõ họ tên</h5>
                    </div>
                </div>
            </div>
        </div>
    <?php
}
function printBol($connect){
    $get = mysqli_query($connect, "SELECT * FROM `agebol` JOIN ageplace ON agebol.`AGEBoLEndPoint` = ageplace.AGEPlaceID WHERE `AGEBoLID` = '" . $_POST['id'] . "'") or die(mysqli_connect_error($connect));
    $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
    $bolID =  createBar128($data['AGEBoLID']);
    ?>
        <div class="row header">
            <div class="col-4 col-m-4 col-s-4 logo">
                <img class="img-responsive" src="img/Logo4.jpg" alt="">
            </div>
            <div class="col-4 col-m-4 col-s-4">
                <h2>AGEXPRESS</h2>
                <h1>Vận Đơn</h1>
            </div>
            <div class="col-4 col-m-4 col-s-4 bardcode"><?php echo $bolID ?></div>
        </div>
        <div class="row content">
            <div class="col-6 col-m-6 col-s-6 height30">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12 title">
                        <h3>Thông Tin Gửi</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Người Gửi:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLSenderName'] ?></div>
                    <div class="col-4 col-m-4 col-s-4">Điện thoại:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo str_replace(substr($data['AGEBoLSenderSdt'],4,4),"****",$data['AGEBoLSenderSdt']);  ?></div>
                    <div class="col-4 col-m-4 col-s-4">Địa chỉ:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLSenderAddress'] ?></div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-6 height30">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12 title">
                        <h3>Thông Tin Nhận</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Người Nhận:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLReceiverName'] ?></div>
                    <div class="col-4 col-m-4 col-s-4">Điện thoại:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo str_replace(substr($data['AGEBoLReceiverSdt'],4,4),"****",$data['AGEBoLReceiverSdt']); ?></div>
                    <div class="col-4 col-m-4 col-s-4">Địa chỉ:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLReceiverAddress'] ?></div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-6">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12 title">
                        <h3>Thông Tin Hàng Hóa</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Hàng hóa:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDecs'] ?></div>
                    <div class="col-4 col-m-4 col-s-4">Trọng lượng:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLWeight'] ?> kg</div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-6">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12 title">
                        <h3>Cước Phí</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Phí vận chuyển:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLTransportFee']) ?> VNĐ</div>
                    <div class="col-4 col-m-4 col-s-4">Thu phí:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLPayer'] == 0 ? "Người gửi" : "Người nhận" ?></div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-6">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12 title">
                        <h3>Thông Tin Đơn Hàng</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Kho phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEPlaceName'] ?></div>
                    <div class="col-4 col-m-4 col-s-4">Hình thức phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDeliveryForm'] == 1 ? "Người nhận tự đến lấy" : "Phát đến địa chỉ nhận" ?></div>
                    <div class="col-4 col-m-4 col-s-4">Hướng dẫn phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDeliveryWay'] ?></div>
                    <div class="col-4 col-m-4 col-s-4">Thu hộ:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLCollection']) ?> VNĐ</div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-6 sign">
                <div class="row">
                    <div class="col-6 col-m-6 col-s-6">
                        <h3>Ký Gửi</h3>
                    </div>
                    <div class="col-6 col-m-6 col-s-6">
                        <h3>Ký Nhận</h3>
                    </div>
                    <div class="col-6 col-m-6 col-s-6">.........., ..../..../......</div>
                    <div class="col-6 col-m-6 col-s-6">.........., ..../..../......</div>
                    <div class="col-6 col-m-6 col-s-6">
                        <h5>Ký và ghi rõ họ tên</h5>
                    </div>
                    <div class="col-6 col-m-6 col-s-6">
                        <h5>Ký và ghi rõ họ tên</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row footer">
            <div class="col-2 col-m-2 col-s-2">
                <img src="img/frame.png" alt="">
            </div>
            <div class="col-8 col-m-8 col-s-8">
                <h4>Công Ty TNHH AGEXPRESS</h4>
                <h5>Số 94, Triệu Thị Trinh, Phường Bình Khánh, Long Xuyên, An Giang</h5>
                <h5>Điện thoại: 0975779603</h5>
            </div>
            <div class="col-2 col-m-2 col-s-2">
                <h5>Develop by TLAIT</h5>
            </div>
        </div>
    <?php
}
?>
