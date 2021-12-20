<?php
session_start();
if (isset($_POST['bolID'])) { // check is set data input
    include_once("connection.php");
    include("TLABarcode.php");
    try { // try to query bol from database and return the component if success
        $get = mysqli_query($connect, "SELECT a.*, b.AGEPlaceName endPoint, c.AGEUserFullName Creater, c.AGEPlace placeCreated  FROM `agebol` a JOIN ageplace b ON a.AGEBoLEndPoint = b.AGEPlaceID JOIN ageuser c ON a.AGEUser = c.AGEUserName WHERE `AGEBoLID` = '" . $_POST['bolID'] . "'") or die(mysqli_connect_error($connect));
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
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['endPoint']?></div>
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
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDeliveryWay']?></div>
                    <div class="col-4 col-m-4 col-s-4">Thu hộ:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLCollection'])?> VNĐ</div>
                    <div class="col-4 col-m-4 col-s-4">Nhân viên tạo:</div>
                    <div class="col-8 col-m-8 col-s-8"><?php echo $data['Creater']?></div>
                    <div class="col-4 col-m-4 col-s-4">Trạng thái:</div>
                    <div class="col-8 col-m-8 col-s-8">
                        <?php 
                            if($data['AGEBoLStatus']==$data['placeCreated']) $status = "Đã nhập kho gửi";
                            else if($data['AGEBoLStatus']==$data['AGEBoLEndPoint']) $status = "Đang phát";
                            else $status = "Đang vận chuyển";
                            echo $status;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row dialogFooter">
            <button
                class="btn btn-info" 
                <?php 
                    $getSS = mysqli_query($connect, "SELECT `AGEPlace` FROM `ageuser` WHERE `AGEUserName` = '".$_SESSION['userName']."'") or die(mysqli_connect_error($connect));
                    $dataSS = mysqli_fetch_array($getSS, MYSQLI_ASSOC);
                    if($data['placeCreated']!==$dataSS['AGEPlace']) echo "disabled";
                ?>
                type="button" onclick="editBol(
                    '<?php echo $data['AGEBoLID']?>',
                    '<?php echo $data['AGEBoLSenderName']?>',
                    '<?php echo $data['AGEBoLSenderSdt']?>',
                    '<?php echo $data['AGEBoLSenderAddress']?>',
                    '<?php echo $data['AGEBoLReceiverName']?>',
                    '<?php echo $data['AGEBoLReceiverSdt']?>',
                    '<?php echo $data['AGEBoLReceiverAddress']?>',
                    '<?php echo $data['AGEBoLDeliveryForm']?>',
                    '<?php echo $data['AGEBoLTransportFee']?>',
                    '<?php echo $data['AGEBoLCollection']?>',
                    '<?php echo $data['AGEBoLPayer']?>',
                    '<?php echo $data['AGEBoLDecs']?>',
                    '<?php echo $data['AGEBoLWeight']?>',
                    '<?php echo $data['AGEBoLEndPoint']?>',
                    '<?php echo $data['AGEBoLDeliveryWay']?>',
                )"
            >Chỉnh Sửa</button>
            <button class="btn btn-success" type="button" onclick="printer('<?php echo $data['AGEBoLID']?>', 'bol')">In Phiếu</button>
            <button class="btn btn-success" type="button">In label</button>
            <button class="btn btn-warning" type="button"
                onclick="cancel('<?php echo $data['AGEBoLID']?>')"
                <?php 
                    $getSS = mysqli_query($connect, "SELECT `AGEPlace` FROM `ageuser` WHERE `AGEUserName` = '".$_SESSION['userName']."'") or die(mysqli_connect_error($connect));
                    $dataSS = mysqli_fetch_array($getSS, MYSQLI_ASSOC);
                    if($data['placeCreated']!==$dataSS['AGEPlace']) echo "disabled";
                ?>
            >Hủy Đơn</button>
            <button class="btn btn-danger" type="button" onclick="hideDialog('#detailDialog')">Đóng</button>
        </div>
    </div>
<?php
    } catch (\Throwable $th) {//if wrong, don't any thing
    }
}
?>