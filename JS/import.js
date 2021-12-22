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
	//get import list
	getImportList();
})

document.addEventListener('DOMContentLoaded', function () {

	Validator({
		form: '#formCheckBol',
		formGroupSelector: '.form-group',
		errorSelector: '.form-message',
		rules: [
			Validator.isRequired('#bolID'),
		],
		onSubmit: function (data) {
			$("#"+data.bolID).prop('checked', true);
			$('#formCheckBol')[0].reset();
		}
	});

});
// get import list
function getImportList() {
	$.post(
		"/process/getImport.php",
		{ action: "getImport" },
		function (response) {
			$(".importList").html(response);
		},
		'text'
	);
}
function checkExport(id) {
	showDialog('#checkExportDialog');
	$('#checkExportDialog .dialogHeader .title').html("Thông Tin Phiếu Xuất");
	getExportInfo(id);
	getBolInExport(id);
}
function getExportInfo(id) {
	$.post(
		"/process/detailExport.php",
		{ exportID: id, action: "getExportInfo" },
		function (response) {
			$("#checkExportDialog #info").html(response);
		},
		'text'
	);
}
function getBolInExport(id) {
	$.post(
		"/process/detailExport.php",
		{ exportID: id, action: "getBolInExportToCheck" },
		function (response) {
			$("#checkExportDialog #listBol").html(response);
		},
		'text'
	);
}
function importBol(id) {
	showConfirm({
		functionName: "processImport(" + id + ")",
		message: 'Vui lòng kiểm tra kỹ các đơn hàng trước khi nhập kho. Trong trường hợp đơn hàng bị thiếu vui lòng xuất đơn hàng đến điểm tiếp theo để được tiếp nhận.<br/>Bạn có muốn tiếp tục?',
	});
}
function processImport(exportID) {
	$.post(
		"/process/importBol.php",
		{ exportID: exportID, action: "importBol" },
		function (response) {
			$("#toast").html(response);
			hideDialog('.dialog.dialogComfirm');
			hideDialog('#checkExportDialog');
			getImportList();
		},
		'text'
	);
}