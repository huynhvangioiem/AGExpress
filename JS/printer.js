//check login
window.onload = function () {
    $.get("process/checkLogin.php", function (response) {
        if (response != "true") window.location = "/login.html";
        else {
            $("body").show();
        }
    });
}
$(document).ready(function () {
    let url = window.location.search;
    params = new URLSearchParams(url);
    var action = params.get("action");
    var id = params.get("id");
    $.post(
        "/process/printer.php",
        { action: action, id: id },
        function (response) {
            $(".container").html(response);
            window.print();
            window.close();
        },
        'text'
    );
})