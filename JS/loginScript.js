//check login
window.onload = function () {
    $.get("./process/checkLogin.php", function (response) {
        if (response) window.location = "/";
        else $("body").show();
    });
}
//xu lu form
document.addEventListener('DOMContentLoaded', function () {
    Validator({
        form: '#formLogin',
        formGroupSelector: '.form-group',
        errorSelector: '.form-message',
        rules: [
            Validator.isRequired('#userName'),
            Validator.isRequired('#password'),
        ],
        onSubmit: function (data) {
            login(data);
        }
    });
})
//submit
function login(data) {
    $.post(
        "./process/login.php",
        { userName: data.userName, password: data.password },
        function (response) {
            $('#toast').html(response)
        },
        'text'
    );
}
//show dialog
$('.forget').click(function () {
    showDialog('#forgetDialog');
})
//close dialog
$('.btnClose').click(function () {
    hideDialog('#forgetDialog');
})