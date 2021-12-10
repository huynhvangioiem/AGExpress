<?php
if (isset($_POST['bolID'])) { // check is set data input
    include_once("connection.php");
    include("TLABarcode.php");
    try { // try to query bol from database and return the component if success
        $get = mysqli_query($connect, "SELECT * FROM `agebol` JOIN ageplace ON agebol.`AGEBoLEndPoint` = ageplace.AGEPlaceID JOIN ageuser ON agebol.AGEUser = ageuser.AGEUserName WHERE `AGEBoLID` = '" . $_POST['bolID'] . "'") or die(mysqli_connect_error($connect));
        $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
        $bolID =  createBar128($data['AGEBoLID']);
?>
    <div class="container">
        <div class="row dialogHeader">
					<div class="col-12 col-m-12 col-s-12">
						<h1 class="title">Chi Tiết Đơn Hàng</h1>
						<div><?php echo $bolID?></div>
					</div>
				</div>
        <div class="row dialogContent">
            <div class="col-6 col-m-6 col-s-12">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12">
                        <h3>Thông Tin Gửi</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Người Gửi:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLSenderName']?></div>
                    <div class="col-4 col-m-4 col-s-4">Điện thoại:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLSenderSdt']?></div>
                    <div class="col-4 col-m-4 col-s-4">Địa chỉ:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLSenderAddress']?></div>
                </div>
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12">
                        <h3>Thông Tin Nhận</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Người Nhận:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLReceiverName']?></div>
                    <div class="col-4 col-m-4 col-s-4">Điện thoại:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLReceiverSdt']?></div>
                    <div class="col-4 col-m-4 col-s-4">Địa chỉ:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLReceiverAddress']?></div>
                    <div class="col-4 col-m-4 col-s-4">Kho phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEPlaceName']?></div>
                </div>
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12">
                        <h3>Thông Tin Hàng Hóa</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Hàng hóa:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDecs']?></div>
                    <div class="col-4 col-m-4 col-s-4">Trọng lượng:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLWeight']?> kg</div>
                </div>
            </div>
            <div class="col-6 col-m-6 col-s-12">
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12">
                        <h3>Cước Phí</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Phí vận chuyển:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLTransportFee'])?> VNĐ</div>
                    <div class="col-4 col-m-4 col-s-4">Thu phí:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLPayer']== 0 ? "Người gửi" : "Người nhận" ?></div>
                </div>
                <div class="row">
                    <div class="col-12 col-m-12 col-s-12">
                        <h3>Thông Tin Đơn Hàng</h3>
                    </div>
                    <div class="col-4 col-m-4 col-s-4">Hình thức phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDeliveryForm'] == 1 ? "Người nhận tự đến lấy" : "Phát đến địa chỉ nhận" ?></div>
                    <div class="col-4 col-m-4 col-s-4">Hướng dẫn phát:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo "Bổ sung hướng dẫn phát"?></div>
                    <div class="col-4 col-m-4 col-s-4">Thu hộ:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLCollection'])?></div>
                    <div class="col-4 col-m-4 col-s-4">Nhân viên tạo:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEUserFullName']?></div>
                    <div class="col-4 col-m-4 col-s-4">Trạng thái:</div>
                    <div class="col-8 col-m-8 col-s-8">
                        <?php 
                            switch ($data['AGEBoLStatus']) {
                                case 0:
                                    echo "Đã nhập kho gửi";
                                    break;
                                case 1:
                                    echo "Đang vận chuyển";
                                    break;
                                case 2:
                                    echo "Đã đến kho phát";
                                    break;
                                case 3:
                                    echo "Phát thành công";
                                    break;
                                case 4:
                                    echo "Chờ phát lại";
                                    break;
                                case 5:
                                    echo "Chuyển hoàn";
                                    break;
                                case 6:
                                    echo "Đã chuyển hoàn";
                                    break;
                                case -1:
                                    echo "Vô hiệu";
                                    break;
                                
                            };
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row dialogFooter">
            <button class="btn btn-info" type="button">Chỉnh Sửa</button>
            <button class="btn btn-success" type="button" onclick="printer('<?php echo $data['AGEBoLID']?>', 'bol')">In Phiếu</button>
            <button class="btn btn-success" type="button">In label</button>
            <button class="btn btn-danger" type="button" onclick="hideDialog('#detailDialog')">Đóng</button>
        </div>
    </div>
<?php
    } catch (\Throwable $th) {//if wrong, don't any thing
    }
}
?>