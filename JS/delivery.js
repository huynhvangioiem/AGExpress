//get data
$(document).ready(function () {
	//check login
	$.get("process/checkLogin.php", function (response) {
		if (response != "true") window.location = "/login.html";
		else $("body").show();
	});
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
			//
			decentralization(response);
		},
		'text'
	);
	//get exportList
	getDeliveryList();
})
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
// 2. show add Dialog
$('#createDelivery').click(function () {
	$.post(
		"/process/createDelivery.php",
		{ action: "createDelivery"},
		function (response) {
			$("#toast").html(response);
			getDeliveryList();
		},
		'text'
	);
})

document.addEventListener('DOMContentLoaded', function () {

	Validator({
		form: '#formAddBol',
		formGroupSelector: '.form-group',
		errorSelector: '.form-message',
		rules: [
			Validator.isRequired('#bolID'),
		],
		onSubmit: function (data) {
			$.post(
				"/process/addBolDelivery.php",
				{ deliveryID: data.deliveryID, bolID: data.bolID },
				function (response) {
					$("#toast").html(response);
					$("#formAddBol #bolID").val("");
					getBol(data.deliveryID);
				},
				'text'
			);
		}
	});

});

// getbollist
function getDeliveryList() {
	$.post(
		"/process/getDelivery.php",
		{ action: "getDelivery" },
		function (response) {
			$(".deliveryList").html(response);
		},
		'text'
	);
}
function addBol(deliveryID) {
	showDialog('#addBolDialog');
	$("#addBolDialog .dialogHeader .title").html("THÔNG TIN DANH SÁCH PHÁT");
	getInfo(deliveryID);
	$("#formAddBol #deliveryID").val(deliveryID);
	getBol(deliveryID);
}
function getInfo(id) {
	$.post(
		"/process/detailDelivery.php",
		{ deliveryID: id, action: "getInfo" },
		function (response) {
			$("#addBolDialog #info").html(response);
		},
		'text'
	);
}
function getBol(id) {
	$.post(
		"/process/detailDelivery.php",
		{ deliveryID: id, action: "getBol" },
		function (response) {
			$("#addBolDialog #listBol").html(response);
		},
		'text'
	);
}
function decentralization(userName) {
	$.post(
		"/process/getOrther.php",
		{ funcName: "getPermissions", userName: userName },
		function (response) {
			var permiss = JSON.parse(response);
			if (permiss['permiss2'] == 0) {
				$("#action").html("");
			}
		},
		'text'
	);
}
function del(id) {
	showConfirm({
		functionName: "processDel(" + id + ")",
		message: 'Bạn có chắc chắn muốn xóa danh sách phát này không?',
	});
}
function processDel(id) {
	$.post(
		"/process/delDelivery.php",
		{ deliveryID: id, action: "delDelivery" },
		function (response) {
			$("#toast").html(response);
			hideDialog('.dialog.dialogComfirm');
			getDeliveryList();
		},
		'text'
	);
}

function deleteBol(delivery, bolid) {
	$.post(
		"/process/delDeliveryBol.php",
		{ deliveryID: delivery, bolID: bolid,  action: "delDeliveryBol" },
		function (response) {
			$("#toast").html(response);
			hideDialog('.dialog.dialogComfirm');
			getBol(delivery);
		},
		'text'
	);
}

function delivery(id) {
	showConfirm({
		functionName: "processDelivery(" + id + ")",
		message: 'Vui lòng kiểm tra kỹ các thông tin trước khi xác nhận danh sách phát. Bạn có muốn tiếp tục?',
	});
}
function processDelivery(id) {
	hideDialog('.dialog.dialogComfirm');
	$.post(
		"/process/delivery.php",
		{ deliveryID: id, action: "delivery" },
		function (response) {
			getInfo(id);
			getBol(id);
			setTimeout(() => {
				printDelivery();
				$("#toast").html(response);
				hideDialog("#addBolDialog");
				getDeliveryList();
			}, 500);
		},
		'text'
	);
}

function printDelivery() {
	window.print();
}

function detailDelivery(id){
	showDialog("#addBolDialog");
	$("#addBolDialog .dialogHeader .title").html("THÔNG TIN DANH SÁCH PHÁT");
	$('#addBolDialog .formAdd').hide();
	getInfo(id);
	getBol(id);
}

function finishBol(deliveryID,bolID) {
	showConfirm({
		functionName: "processFinishBol("+deliveryID+","+bolID+")",
		message: 'Bạn xác nhận rằng đơn hàng này đã được phát thành công.',
	});
}
function processFinishBol(deliveryID,bolID) {
	$.post(
		"/process/finishBol.php",
		{ bolID: bolID, action: "success"},
		function (response) {
			$("#toast").html(response);
			hideDialog('.dialog.dialogComfirm');
			getBol(deliveryID);
		},
		'text'
	);
}