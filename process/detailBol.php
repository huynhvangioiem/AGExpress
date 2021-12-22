<?php
/*
* version 1.0
* last Update: 20/12/21
* start: 200 OK
*/
session_start();
if (!isset($_POST['action'])) {
    if (!isset($_SESSION['userName']) || !isset($_POST['bolID']) || !is_numeric($_POST['bolID'])) { // check login and input data
        echo '<script>window.location ="/";</script>';
    } else {
        include_once("connection.php");
        include("TLABarcode.php");
        try { // try to query bol from database and return the component if success
            $get = mysqli_query($connect, "SELECT a.*, b.AGEPlaceName endPoint, c.AGEUserFullName Creater, c.AGEPlace placeCreated, d.AGEPlaceName placeCreateName  FROM `agebol` a JOIN ageplace b ON a.AGEBoLEndPoint = b.AGEPlaceID JOIN ageuser c ON a.AGEUser = c.AGEUserName JOIN ageplace d ON c.AGEPlace = d.AGEPlaceID WHERE `AGEBoLID` = '" . $_POST['bolID'] . "'") or die(mysqli_connect_error($connect));
            $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
            $bolID =  createBar128($data['AGEBoLID']);
            if ($data['AGEUser'] != $_SESSION['userName']) $action = '
                <button class="btn btn-success" type="button" onclick="printer(' . $data['AGEBoLID'] . ', \'bol\')">In Phiếu</button>
                <button class="btn btn-danger" type="button" onclick="hideDialog(\'#detailDialog\')">Đóng</button>
            ';
            else $action = '
                <button class="btn btn-info" type="button" onclick="editBol(
                    \'' . $data['AGEBoLID'] . '\',
                    \'' . $data['AGEBoLSenderName'] . '\',
                    \'' . $data['AGEBoLSenderSdt'] . '\',
                    \'' . $data['AGEBoLSenderAddress'] . '\',
                    \'' . $data['AGEBoLReceiverName'] . '\',
                    \'' . $data['AGEBoLReceiverSdt'] . '\',
                    \'' . $data['AGEBoLReceiverAddress'] . '\',
                    \'' . $data['AGEBoLDeliveryForm'] . '\',
                    \'' . $data['AGEBoLTransportFee'] . '\',
                    \'' . $data['AGEBoLCollection'] . '\',
                    \'' . $data['AGEBoLPayer'] . '\',
                    \'' . $data['AGEBoLDecs'] . '\',
                    \'' . $data['AGEBoLWeight'] . '\',
                    \'' . $data['AGEBoLEndPoint'] . '\',
                    \'' . $data['AGEBoLDeliveryWay'] . '\'
                )" >Chỉnh Sửa</button>
                <button class="btn btn-success" type="button" onclick="printer(' . $data['AGEBoLID'] . ', \'bol\')">In Phiếu</button>
                <button class="btn btn-warning" type="button" onclick="cancel(' . $data["AGEBoLID"] . ')">Hủy Đơn</button>
                <button class="btn btn-danger" type="button" onclick="hideDialog(\'#detailDialog\')">Đóng</button>
            ';

            $getHistory = mysqli_query($connect, "SELECT aa.*, bb.AGEPlaceName placeSendName, cc.AGEPlaceName placeReceiverName, dd.AGEPlaceName placeExpName, ee.AGEPlaceName placeDestiName, ff.AGEPlaceName placeOrther FROM ( SELECT a.AGEBol, a.AGEHistoryTime, a.AGEHistoryStatus, c.AGEPlace placeSend, b.AGEBoLEndPoint placeReceiver, d.AGEUser exporter, e.AGEPlace placeExport, d.AGEDestination placeDestination FROM `agehistory` a JOIN agebol b ON a.AGEBol = b.AGEBoLID JOIN ageuser c ON b.AGEUser = c.AGEUserName LEFT JOIN ageexport d ON a.AGEHistoryStatus = d.AGEExportID LEFT JOIN ageuser e ON d.AGEUser = e.AGEUserName WHERE `AGEBol` = '" . $data['AGEBoLID'] . "' ) aa LEFT JOIN ageplace bb ON aa.placeSend = bb.AGEPlaceID LEFT JOIN ageplace cc ON aa.placeReceiver = cc.AGEPlaceID LEFT JOIN ageplace dd ON aa.placeExport = dd.AGEPlaceID LEFT JOIN ageplace ee ON aa.placeDestination = ee.AGEPlaceID LEFT JOIN ageplace ff ON aa.AGEHistoryStatus = ff.AGEPlaceID");
            $historyBol = "";
            while ($dataHistory = mysqli_fetch_array($getHistory, MYSQLI_ASSOC)) {
                if ($dataHistory['AGEHistoryStatus'] == $dataHistory['placeSend']) $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Chấp nhận gửi tại ' . $dataHistory['placeSendName'] . '</div>';
                if ($dataHistory['AGEHistoryStatus'] == $dataHistory['placeReceiver']) $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Đã đến kho phát ' . $dataHistory['placeReceiverName'] . '</div>';
                if ($dataHistory['exporter'] != "") $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Đang vận chuyển từ ' . $dataHistory['placeExpName'] . ' đến ' . $dataHistory['placeDestiName'] . '</div>';
                if($dataHistory['AGEHistoryStatus']==200) $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Đã phát thành công</div>';
                else if ($dataHistory['AGEHistoryStatus'] != $dataHistory['placeSend'] && $dataHistory['AGEHistoryStatus'] != $dataHistory['placeReceiver'] && $dataHistory['exporter'] == "")
                    $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Đã đến kho ' . $dataHistory['placeOrther'] . '</div>';
            }

?>
            <div class="container">
                <div class="row dialogHeader">
                    <div class="col-12 col-m-12 col-s-12">
                        <h1 class="title">Chi Tiết Đơn Hàng</h1>
                        <div><?php echo $bolID ?></div>
                    </div>
                </div>
                <div class="row dialogContent">
                    <div class="col-6 col-m-6 col-s-12">
                        <div class="row">
                            <div class="col-12 col-m-12 col-s-12">
                                <h3>Thông Tin Gửi</h3>
                            </div>
                            <div class="col-4 col-m-4 col-s-4">Người Gửi:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLSenderName'] ?></div>
                            <div class="col-4 col-m-4 col-s-4">Điện thoại:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLSenderSdt'] ?></div>
                            <div class="col-4 col-m-4 col-s-4">Địa chỉ:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLSenderAddress'] ?></div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-m-12 col-s-12">
                                <h3>Thông Tin Nhận</h3>
                            </div>
                            <div class="col-4 col-m-4 col-s-4">Người Nhận:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLReceiverName'] ?></div>
                            <div class="col-4 col-m-4 col-s-4">Điện thoại:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLReceiverSdt'] ?></div>
                            <div class="col-4 col-m-4 col-s-4">Địa chỉ:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLReceiverAddress'] ?></div>
                            <div class="col-4 col-m-4 col-s-4">Kho phát:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['endPoint'] ?></div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-m-12 col-s-12">
                                <h3>Thông Tin Hàng Hóa</h3>
                            </div>
                            <div class="col-4 col-m-4 col-s-4">Hàng hóa:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDecs'] ?></div>
                            <div class="col-4 col-m-4 col-s-4">Trọng lượng:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLWeight'] ?> kg</div>
                        </div>
                    </div>
                    <div class="col-6 col-m-6 col-s-12">
                        <div class="row">
                            <div class="col-12 col-m-12 col-s-12">
                                <h3>Cước Phí</h3>
                            </div>
                            <div class="col-4 col-m-4 col-s-4">Phí vận chuyển:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLTransportFee']) ?> VNĐ</div>
                            <div class="col-4 col-m-4 col-s-4">Thu phí:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLPayer'] == 0 ? "Người gửi" : "Người nhận" ?></div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-m-12 col-s-12">
                                <h3>Thông Tin Đơn Hàng</h3>
                            </div>
                            <div class="col-4 col-m-4 col-s-4">Hình thức phát:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDeliveryForm'] == 1 ? "Người nhận tự đến lấy" : "Phát đến địa chỉ nhận" ?></div>
                            <div class="col-4 col-m-4 col-s-4">Hướng dẫn phát:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['AGEBoLDeliveryWay'] ?></div>
                            <div class="col-4 col-m-4 col-s-4">Thu hộ:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo number_format($data['AGEBoLCollection']) ?> VNĐ</div>
                            <div class="col-4 col-m-4 col-s-4">Trạng thái:</div>
                            <div class="col-8 col-m-8 col-s-8">
                                <?php
                                if ($data['AGEBoLStatus'] == $data['placeCreated']) $status = "Đã nhập kho gửi";
                                else if ($data['AGEBoLStatus'] == $data['AGEBoLEndPoint']) $status = "Đang phát";
                                else $status = "Đang vận chuyển";
                                echo $status;
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-m-12 col-s-12">
                                <h3>Chập Nhận Gửi Bởi: </h3>
                            </div>
                            <div class="col-4 col-m-4 col-s-4">Nhân viên:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['Creater'] ?></div>
                            <div class="col-4 col-m-4 col-s-4">Kho nhận:</div>
                            <div class="col-8 col-m-8 col-s-8"><?php echo $data['placeCreateName'] ?></div>
                        </div>
                    </div>
                    <div class="col-12 col-m-12 col-s-12">
                        <div class="row">
                            <div class="col-12 col-m-12 col-s-12" id="historyBol">
                                <h2>Lịch Sử Đơn Hàng</h2>
                            </div>
                            <?php echo $historyBol; ?>
                        </div>
                    </div>
                </div>
                <div class="row dialogFooter"><?php echo $action ?></div>
            </div>
        <?php
        } catch (\Throwable $th) { //if wrong, don't any thing
        }
    }
} else {
    if ($_POST['action'] == 'track' && is_numeric($_POST['bolID'])) {
        include("TLABarcode.php");
        include_once("connection.php");
        $get = mysqli_query($connect, "SELECT a.*, b.AGEPlaceName endPoint, c.AGEUserFullName Creater, c.AGEPlace placeCreated, d.AGEPlaceName placeCreateName  FROM `agebol` a JOIN ageplace b ON a.AGEBoLEndPoint = b.AGEPlaceID JOIN ageuser c ON a.AGEUser = c.AGEUserName JOIN ageplace d ON c.AGEPlace = d.AGEPlaceID WHERE `AGEBoLID` = '" . $_POST['bolID'] . "'") or die(mysqli_connect_error($connect));
        $data = mysqli_fetch_array($get, MYSQLI_ASSOC);
        if (mysqli_num_rows($get) == 1) {
            $bolID =  createBar128($data['AGEBoLID']);
            $getHistory = mysqli_query($connect, "SELECT aa.*, bb.AGEPlaceName placeSendName, cc.AGEPlaceName placeReceiverName, dd.AGEPlaceName placeExpName, ee.AGEPlaceName placeDestiName, ff.AGEPlaceName placeOrther FROM ( SELECT a.AGEBol, a.AGEHistoryTime, a.AGEHistoryStatus, c.AGEPlace placeSend, b.AGEBoLEndPoint placeReceiver, d.AGEUser exporter, e.AGEPlace placeExport, d.AGEDestination placeDestination FROM `agehistory` a JOIN agebol b ON a.AGEBol = b.AGEBoLID JOIN ageuser c ON b.AGEUser = c.AGEUserName LEFT JOIN ageexport d ON a.AGEHistoryStatus = d.AGEExportID LEFT JOIN ageuser e ON d.AGEUser = e.AGEUserName WHERE `AGEBol` = '" . $data['AGEBoLID'] . "' ) aa LEFT JOIN ageplace bb ON aa.placeSend = bb.AGEPlaceID LEFT JOIN ageplace cc ON aa.placeReceiver = cc.AGEPlaceID LEFT JOIN ageplace dd ON aa.placeExport = dd.AGEPlaceID LEFT JOIN ageplace ee ON aa.placeDestination = ee.AGEPlaceID LEFT JOIN ageplace ff ON aa.AGEHistoryStatus = ff.AGEPlaceID");
            $historyBol = "";
            while ($dataHistory = mysqli_fetch_array($getHistory, MYSQLI_ASSOC)) {
                if ($dataHistory['AGEHistoryStatus'] == $dataHistory['placeSend']) $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Chấp nhận gửi tại ' . $dataHistory['placeSendName'] . '</div>';
                if ($dataHistory['AGEHistoryStatus'] == $dataHistory['placeReceiver']) $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Đã đến kho phát ' . $dataHistory['placeReceiverName'] . '</div>';
                if ($dataHistory['exporter'] != "") $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Đang vận chuyển từ ' . $dataHistory['placeExpName'] . ' đến ' . $dataHistory['placeDestiName'] . '</div>';
                if ($dataHistory['AGEHistoryStatus'] != $dataHistory['placeSend'] && $dataHistory['AGEHistoryStatus'] != $dataHistory['placeReceiver'] && $dataHistory['exporter'] == "")
                    $historyBol .= '<div class="col-12 col-m-12 col-s-12"> * ' . date_format(date_create($dataHistory['AGEHistoryTime']), "d/m/Y H:i") . ': Đã đến kho ' . $dataHistory['placeOrther'] . '</div>';
            }
        ?>
            <div class="row">
                <div class="col-12 col-m-12 col-s-12" id="historyBol">
                    <h2>Lịch Sử Đơn Hàng</h2>
                </div>
                <?php echo $historyBol; ?>
            </div>
        <?php
        }
    }
}
?>