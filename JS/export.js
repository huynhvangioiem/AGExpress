// check login
window.onload = function () {
	$.get("process/checkLogin.php", function (response) {
		if (response != "true") window.location = "/login.html";
		else $("body").show();
	});
}
//logOut
function logout() {
	$.get(
		"/process/logout.php",
		function (response) {
			window.location = "/";
		},
		'text'
	);
}
//get data
$(document).ready(function () {
	//profile
	$.post(
		"/process/getOrther.php",
		{ funcName: "getSession" },
		function (response) {
			$.post(
				"/process/getProfile.php",
				{ userName: response },
				function (response) {
					$(".profile").html(response);
				},
				'text'
			);
		},
		'text'
	);
	//get exportList
	getExportList();
})

// 2. show add Dialog
$('#createExport').click(function () {
	showDialog('#createExportDialog');
	//get place
	getPlace();
	getShipmentOptions();

})
function getPlace(value) {
	$.post(
		"/process/getOptionsUserPlace.php",
		{ action: 'getOptionsUserPlace', message: 'Chọn kho cần xuất đến', val: value },
		function (response) {
			$("#destination").html(response);
		},
		'text'
	);
}
function getShipmentOptions(value) {
	$.post(
		"/process/getOptionsShipment.php",
		{ action: 'getOptionsShipment', message: 'Chọn chuyến xe vận chuyển', val: value },
		function (response) {
			$("#shipment").html(response);
			// $("#endPoint_").html(response);
		},
		'text'
	);
}

// Form validate and submit
document.addEventListener('DOMContentLoaded', function () {

	Validator({
		form: '#formCreateExport',
		formGroupSelector: '.form-group',
		errorSelector: '.form-message',
		rules: [
			Validator.isRequired('#timeExport'),
			Validator.isSelected('#shipment', 'Vui lòng chọn chuyến xe vận chuyển!'),
			Validator.isSelected('#destination', 'Vui lòng chọn kho nhận!')
		],
		onSubmit: function (data) {
			if (data.exportID == "") {
				$.post(
					"/process/createExport.php",
					{
						timeExport: data.timeExport,
						shipment: data.shipment,
						destination: data.destination,
					},
					function (response) {
						$("#toast").html(response);
						$("#formCreateExport")[0].reset();
						hideDialog('#createExportDialog');
						getExportList();
					},
					'text'
				);
			} else {
				$.post(
					"/process/editExport.php",
					{
						exportID: data.exportID,
						timeExport: data.timeExport,
						shipment: data.shipment,
						destination: data.destination,
					},
					function (response) {
						$("#toast").html(response);
						resetDialogCreate();
						getExportList();
					},
					'text'
				);
			}
		}
	});

	Validator({
		form: '#formAddBolToExport',
		formGroupSelector: '.form-group',
		errorSelector: '.form-message',
		rules: [
			Validator.isRequired('#bolID'),
		],
		onSubmit: function (data) {
			$.post(
				"/process/addBolToExport.php",
				{ exportID: data.exportID, bolID: data.bolID },
				function (response) {
					$("#toast").html(response);
					$("#formAddBolToExport #bolID").val("");
					getBolInExport(data.exportID);
				},
				'text'
			);
		}
	});

});
// getbollist
function getExportList() {
	$.post(
		"/process/getExport.php",
		{ action: "getExport" },
		function (response) {
			$(".exportList").html(response);
		},
		'text'
	);
}
//edit export
function edit(exportID, exportTime, shipmentID, destination) {
	showDialog('#createExportDialog');
	$("#createExportDialog .title").html("Chỉnh Sửa Thông Tin Phiếu Xuất");
	$("#formCreateExport #exportID").val(exportID);
	$("#formCreateExport #timeExport").val(exportTime);
	getShipmentOptions(shipmentID);
	getPlace(destination);
	$("#formCreateExport #btnReset").hide();
	$("#formCreateExport #btnSubmit").html("Cập Nhật");
}
function resetDialogCreate() {
	$("#formCreateExport")[0].reset();
	$("#createExportDialog .title").html("Tạo Phiếu Xuất");
	$("#formCreateExport #btnReset").show();
	$("#formCreateExport #btnSubmit").html("Tạo phiếu xuất");
	hideDialog('#createExportDialog');
}
function cancel(id) {
	showConfirm({
		functionName: "processCancel('" + id + "')",
		message: 'Thao tác này không thể khôi phục. Bạn có chắc chắn rằng muốn hủy phiếu xuất  "' + id + '" không?',
	});
}
function processCancel(id) {
	$.post(
		"/process/cancelExport.php",
		{ id: id },
		function (response) {
			$('#toast').html(response);
			hideDialog('.dialog.dialogComfirm');
			hideDialog('#addBolToExportDialog');
			getExportList();
		},
		'text'
	);
}
function addBolToExport(id) {
	showDialog('#addBolToExportDialog');
	getExportInfo(id);
	$("#formAddBolToExport #exportID").val(id);
	getBolInExport(id);
	$('#addBolToExportDialog .dialogHeader .title').html("Thêm Đơn Hàng Vào Phiếu Xuất");


}
function getExportInfo(id) {
	$.post(
		"/process/detailExport.php",
		{ exportID: id, action: "getExportInfo" },
		function (response) {
			$("#addBolToExportDialog #info").html(response);
		},
		'text'
	);
}
function getBolInExport(id) {
	$.post(
		"/process/detailExport.php",
		{ exportID: id, action: "getBolInExport" },
		function (response) {
			$("#addBolToExportDialog #listBol").html(response);
		},
		'text'
	);
}
function exportBol(id) {
	$.post(
		"/process/exportBol.php",
		{ exportID: id, action: "exportBol" },
		function (response) {
			getExportInfo(id);
			getBolInExport(id);
			setTimeout(() => {
				printExport();
				$("#toast").html(response);
				hideDialog('#addBolToExportDialog');
				getExportList();
			}, 1000);
		},
		'text'
	);
}
function detailExport(id) {
	showDialog('#addBolToExportDialog');
	getExportInfo(id);
	getBolInExport(id);
	$('#addBolToExportDialog .dialogHeader .title').html("Thông Tin Phiếu Xuất Kho");
	$('#addBolToExportDialog .formAdd').hide();
}
function del(bolID, exportID) {
	showConfirm({
		functionName: "processDel(" + bolID + "," + exportID + ")",
		message: 'Bạn chắn chắn rằng muốn xóa đơn hàng "' + bolID + '" khỏi phiếu xuất "' + exportID + '" Không?',
	});
}
function processDel(bolID, exportID) {
	$.post(
		"/process/delBolOnExport.php",
		{ exportID: exportID, bolID: bolID, action: "delBolOnExport" },
		function (response) {
			$("#toast").html(response);
			hideDialog('.dialog.dialogComfirm');
			getBolInExport(exportID);
		},
		'text'
	);
}

function printExport() {
	window.print();
}