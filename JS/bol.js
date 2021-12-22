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
			//
			$.post(
				"/process/getProfile.php",
				{ userName: response },
				function (response) {
					$(".profile").html(response);
				},
				'text'
			);
			decentralization(response);
		},
		'text'
	);
	//getbollist
	getBol();
})
// 2. show add User Dialog
$('#createBol').click(function () {
	showDialog('#createBolDialog');
	getPlace();
})
function getPlace(value) {
	$.post(
		"/process/getOptionsUserPlace.php",
		{ action: 'getOptionsUserPlace', message: 'Chọn kho phát', val: value},
		function (response) {
			$("#endPoint").html(response);
			$("#endPoint_").html(response);
		},
		'text'
	);
}
// Form validate and submit
document.addEventListener('DOMContentLoaded', function () {

	Validator({
		form: '#formCreateBol',
		formGroupSelector: '.form-group',
		errorSelector: '.form-message',
		rules: [
			Validator.isRequired('#senderTel'),
			Validator.isRequired('#senderName'),
			Validator.isRequired('#senderAddress'),
			Validator.isRequired('#receiverTel'),
			Validator.isRequired('#receiverName'),
			Validator.isRequired('#receiverAddress'),
			Validator.isRequired('#weight'),
			Validator.isRequired('#transportFee'),
			Validator.isTel('#senderTel'),
			Validator.isTel('#receiverTel'),
			Validator.isSelected('#endPoint', 'Vui lòng chọn địa điểm phát đơn!'),
		],
		onSubmit: function (data) {
			$.post(
				"/process/createBol.php",
				{
					senderTel: data.senderTel,
					senderName: data.senderName,
					senderAddress: data.senderAddress,
					receiverTel: data.receiverTel,
					receiverName: data.receiverName,
					receiverAddress: data.receiverAddress,
					description: data.description,
					weight: data.weight,
					collection: data.collection == "" ? 0 : data.collection,
					deliveryForm: data.deliveryForm ? 1 : 0,
					transportFee: data.transportFee,
					payer: data.payer ? 1 : 0,
					endPoint: data.endPoint,
					deliveryWay: data.deliveryWay
				},
				function (response) {
					$("#toast").html(response);
					$("#formCreateBol")[0].reset();
					hideDialog('#createBolDialog');
					getBol();
				},
				'text'
			);
		}
	});

	Validator({
		form: '#formEditBol',
		formGroupSelector: '.form-group',
		errorSelector: '.form-message',
		rules: [
			Validator.isRequired('#senderTel_'),
			Validator.isRequired('#senderName_'),
			Validator.isRequired('#senderAddress_'),
			Validator.isRequired('#receiverTel_'),
			Validator.isRequired('#receiverName_'),
			Validator.isRequired('#receiverAddress_'),
			Validator.isRequired('#weight_'),
			Validator.isRequired('#transportFee_'),
			Validator.isTel('#senderTel_'),
			Validator.isTel('#receiverTel_'),
			Validator.isSelected('#endPoint_', 'Vui lòng chọn địa điểm phát đơn!'),
		],
		onSubmit: function (data) {
			$.post(
				"/process/editBol.php",
				{
					bolID: data.bolID,
					senderTel: data.senderTel_,
					senderName: data.senderName_,
					senderAddress: data.senderAddress_,
					receiverTel: data.receiverTel_,
					receiverName: data.receiverName_,
					receiverAddress: data.receiverAddress_,
					description: data.description_,
					weight: data.weight_,
					collection: data.collection_ == "" ? 0 : data.collection_,
					deliveryForm: data.deliveryForm_ ? 1 : 0,
					transportFee: data.transportFee_,
					payer: data.payer_ ? 1 : 0,
					endPoint: data.endPoint_,
					deliveryWay: data.deliveryWay_
				},
				function (response) {
					$("#toast").html(response);
					$("#formEditBol")[0].reset();
					hideDialog('#editBolDialog');
					hideDialog('#detailDialog');
					getBol();
				},
				'text'
			);
		}
	});
});
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
// getbollist
function getBol() {
	$.post(
		"/process/getBol.php",
		{ action: "getBol" },
		function (response) {
			$(".bolList").html(response);
		},
		'text'
	);
}
function detail(bolID) {
	showDialog('#detailDialog');
	$.post(
		"/process/detailBol.php",
		{ bolID: bolID },
		function (response) {
			$("#detailDialog").html(response);
		},
		'text'
	);
}
function printer(bolID, action) {
	window.open("printer.html?action=" + action + "&id=" + bolID, "printWindow");
}
function editBol(bolID, senderName_, senderTel_, senderAddress_, receiverName_, receiverTel_, receiverAddress_, deliveryForm_, transportFee_, collection_, payer_, description_, weight_, endPoint_, deliveryWay_) {
	showDialog('#editBolDialog');
	$("#formEditBol #bolID").val(bolID);
	$("#formEditBol #senderTel_").val(senderTel_);
	$("#formEditBol #senderName_").val(senderName_);
	$("#formEditBol #senderAddress_").val(senderAddress_);
	$("#formEditBol #receiverTel_").val(receiverTel_);
	$("#formEditBol #receiverName_").val(receiverName_);
	$("#formEditBol #receiverAddress_").val(receiverAddress_);
	getPlace(endPoint_);
	$("#formEditBol #description_").val(description_);
	$("#formEditBol #weight_").val(weight_);
	$("#formEditBol #deliveryWay_").val(deliveryWay_);
	$("#formEditBol #collection_").val(collection_);
	$("#formEditBol #transportFee_").val(transportFee_);
	if(payer_ == 1) $("#formEditBol #payer_").prop('checked', true);
	if(deliveryForm_ == 1) $("#formEditBol #deliveryForm_").prop('checked', true);
}
function decentralization(userName) {
	$.post(
			"/process/getOrther.php",
			{ funcName: "getPermissions", userName: userName },
			function (response) {
					var permiss = JSON.parse(response);
					if(permiss['permiss2'] == 0){
							$("#action").html("");
					}
			},
			'text'
	);
}