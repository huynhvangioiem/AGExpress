// 1. check login
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
	//getbollist
	getBol();
})
// 2. show add User Dialog
$('#createBol').click(function () {
	showDialog('#createBolDialog');
	$.post(
        "/process/getOptionsUserPlace.php",
        { action: 'getOptionsUserPlace', message:'Chọn kho phát'},
        function (response) {
            $("#endPoint").html(response);
        },
        'text'
    );
})
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
					senderTel: 			data.senderTel,
					senderName: 		data.senderName,
					senderAddress: 		data.senderAddress,
					receiverTel: 		data.receiverTel,
					receiverName: 		data.receiverName,
					receiverAddress: 	data.receiverAddress,
					description: 		data.description,
					weight: 			data.weight,
					collection: 		data.collection == "" ? 0 : data.collection ,
					deliveryForm: 		data.deliveryForm ? 1 : 0,
					transportFee: 		data.transportFee,
					payer: 				data.payer ? 1 : 0,
					endPoint: 			data.endPoint
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
});

// getbollist
function getBol() {
	$.post(
		"/process/getBol.php",
		{action: "getBol"},
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
		{bolID: bolID},
		function (response) {
			$("#detailDialog").html(response);
		},
		'text'
	);
}
function printer(bolID,action) {
	window.open("printer.html?action="+action+"&id="+bolID, "printWindow");
}