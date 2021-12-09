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
})
// 2. show add User Dialog
$('#createShipment').click(function () {
  showDialog('#createShipmentDialog');
  $.post(
    "/process/getOptionsUserPlace.php",
    { action: 'getOptionsUserPlace', message: 'Chọn điểm xuất phát' },
    function (response) {
      $("#from").html(response);
    },
    'text'
  );
  $.post(
    "/process/getOptionsUserPlace.php",
    { action: 'getOptionsUserPlace', message: 'Chọn điểm đến' },
    function (response) {
      $("#to").html(response);
    },
    'text'
  );
})
document.addEventListener('DOMContentLoaded', function () {

  Validator({
    form: '#formCreateShipment',
    formGroupSelector: '.form-group',
    errorSelector: '.form-message',
    rules: [
      Validator.isRequired('#driverName'),
      Validator.isRequired('#licensePlates'),
      Validator.isRequired('#start'),
      Validator.isRequired('#end'),
      Validator.isSelected('#from', 'Vui lòng chọn địa điểm xuất phát!'),
      Validator.isSelected('#to', 'Vui lòng chọn địa điểm đến!'),
    ],
    onSubmit: function (data) {
      console.log(data);
      // $.post(
      //   "/process/createBol.php",
      //   {
      //     senderTel: data.senderTel,
      //     senderName: data.senderName,
      //     senderAddress: data.senderAddress,
      //     receiverTel: data.receiverTel,
      //     receiverName: data.receiverName,
      //     receiverAddress: data.receiverAddress,
      //     description: data.description,
      //     weight: data.weight,
      //     collection: data.collection == "" ? 0 : data.collection,
      //     deliveryForm: data.deliveryForm ? 1 : 0,
      //     transportFee: data.transportFee,
      //     payer: data.payer ? 1 : 0,
      //     endPoint: data.endPoint
      //   },
      //   function (response) {
      //     $("#toast").html(response);
      //     $("#formCreateBol")[0].reset();
      //     hideDialog('#createBolDialog');
      //     getBol();
      //   },
      //   'text'
      // );
    }
  });
});