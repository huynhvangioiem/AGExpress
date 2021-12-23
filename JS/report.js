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
			// decentralization(response);
		},
		'text'
	);
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
document.addEventListener('DOMContentLoaded', function () {
  Validator({
		form: '#formReport',
		formGroupSelector: '.form-group',
		errorSelector: '.form-message',
		rules: [
			Validator.isRequired('#timeStart'),
			Validator.isRequired('#timeEnd'),
			Validator.isRequired('#reportAction'),
		],
		onSubmit: function (data) {
			$.post(
				"/process/report.php",
				{ timeStart: data.timeStart, timeEnd: data.timeEnd, reportAction: data.reportAction},
				function (response) {
					$(".reportList").html(response);
				},
				'text'
			);
		}
	});

});