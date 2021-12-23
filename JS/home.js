
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
                    decentralization(response);
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
function decentralization(userName) {
    $.post(
        "/process/getOrther.php",
        { funcName: "getPermissions", userName: userName },
        function (response) {
            var permiss = JSON.parse(response);
            if(permiss['permiss1'] == 1){ window.location="/user.html"}
            if(permiss['permiss4'] == 1){ window.location="/shipment.html"}
            if(permiss['permiss2'] == 1){ window.location="/bol.html"}
        },
        'text'
    );
  }