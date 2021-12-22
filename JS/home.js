
//2. get data to profile
$(document).ready(function () {
    $.get("process/checkLogin.php", function (response) {
        if (response != "true") window.location = "/login.html";
        else {
            $("body").show();
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
        }
    });
})
//3. logOut
function logout() {
    $.get(
        "/process/logout.php",
        function (response) {
          window.location = "/";
        },
        'text'
    );
}