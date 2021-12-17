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
  //shipment list
  getShipment();
})
// 2. show add User Dialog
$('#createShipment').click(function () {
  showDialog('#createShipmentDialog');
  //get options user place for startPoint
  $.post(
    "/process/getOptionsUserPlace.php",
    { action: 'getOptionsUserPlace', message: 'Chọn điểm xuất phát' },
    function (response) {
      $("#from").html(response);
    },
    'text'
  );
  //get options user place for endPoint
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
      if (data.shipmentId == "") {
        $.post(
          "/process/createShipment.php",
          {
            driverName: data.driverName,
            licensePlates: data.licensePlates,
            from: data.from,
            to: data.to,
            start: data.start,
            end: data.end
          },
          function (response) {
            $("#toast").html(response);
            $("#formCreateShipment")[0].reset();
            hideDialog('#createShipmentDialog');
            getShipment();
          },
          'text'
        );
      } else {
        $.post(
          "/process/editShipment.php",
          {
            shipmentId: data.shipmentId,
            driverName: data.driverName,
            licensePlates: data.licensePlates,
            from: data.from,
            to: data.to,
            start: data.start,
            end: data.end
          },
          function (response) {
            $("#toast").html(response);
            cancelEdit();
            getShipment();
          },
          'text'
        );
      }
    }
  });
});


// show list options user place
function listOptionsUserPlace(selector, val) {
  $.post(
    "/process/getOptionsUserPlace.php",
    { action: 'getOptionsUserPlace', val: val, message: "Vui lòng chọn địa điểm" },
    function (response) {
      $(selector).html(response);
    },
    'text'
  );
}

//func get shipment
function getShipment() {
  $.post(
    "/process/getShipment.php",
    { action: "getShipment" },
    function (response) {
      $(".shipmentList").html(response);
    },
    'text'
  );
}
function edit(id, name, bks, from, to, start, end) {
  showDialog('#createShipmentDialog');
  $("#createShipmentDialog .title").html("Chỉnh Sửa Thông Tin Chuyến Hàng");
  $("#createShipmentDialog #shipmentId").val(id);
  $("#createShipmentDialog #driverName").val(name);
  $("#createShipmentDialog #licensePlates").val(bks);
  $("#createShipmentDialog #start").val(start);
  $("#createShipmentDialog #end").val(end);
  listOptionsUserPlace("#createShipmentDialog #from", from);
  listOptionsUserPlace("#createShipmentDialog #to", to);
  $("#createShipmentDialog .dialogFooter").html(`
    <button class="btn btn-success" type="submit">Cập nhật</button>
    <button class="btn btn-danger" type="button" onclick="cancelEdit()">Hủy</button>
  `);
}

function cancelEdit() {
  $("#createShipmentDialog .title").html("Tạo Chuyến Hàng");
  $("#createShipmentDialog .dialogFooter").html(`
    <button class="btn btn-warning" type="reset">Nhập lại</button>
    <button class="btn btn-success" type="submit">Tạo chuyến hàng</button>
    <button class="btn btn-danger" type="button" onclick="hideDialog('#createShipmentDialog')">Hủy</button>
  `);
  $("#formCreateShipment")[0].reset();
  hideDialog('#createShipmentDialog');

}
function cancel(id) {
  showConfirm({
    functionName: "processCancel('" + id + "')",
    message: 'Thao tác này không thể khôi phục. Bạn có chắc chắn rằng muốn hủy chuyến hàng  "' + id + '" không?',
  });
}
function processCancel(id) {
  $.post(
      "/process/cancelShipment.php",
      {id: id},
      function (response) {
          $('#toast').html(response);
          hideDialog('.dialog.dialogComfirm');
          getShipment();
      },
      'text'
  );
}

function play(id) {
  showConfirm({
    functionName: "processPlay('" + id + "')",
    message: 'Thao tác này không thể khôi phục. Bạn có chắc chắn rằng chuyến hàng "' + id + '" đã đủ điều kiện để xuất phát không?',
  });
}
function processPlay(id) {
  $.post(
      "/process/playShipment.php",
      {id: id},
      function (response) {
          $('#toast').html(response);
          hideDialog('.dialog.dialogComfirm');
          getShipment();
      },
      'text'
  );
}
