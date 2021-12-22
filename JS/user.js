$(document).ready(function () {
    // check login 
    $.get("process/checkLogin.php", function (response) {
        if (response != "true") window.location = "/login.html";
        else $("body").show();
    });
    $.post(
        "/process/getOrther.php",
        { funcName: "getSession" },
        function (response) {
            //get data to profile
            $.post(
                "/process/getProfile.php",
                { userName: response },
                function (response) {
                    $(".profile").html(response);
                },
                'text'
            );
            //decentralization
            decentralization(response);
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
// 2. show add User Dialog
$('#callAddUser').click(function () {
    showDialog('#addUserDialog');
    listOptionsUserType("#formAddUser #userType");
    listOptionsUserPlace("#formAddUser #userPlace");
})
// 3. xu ly form
document.addEventListener('DOMContentLoaded', function () {

    Validator({
        form: '#formAddUser',
        formGroupSelector: '.form-group',
        errorSelector: '.form-message',
        rules: [
            Validator.isRequired('#userName'),
            Validator.isTel('#userName'),
            Validator.isRequired('#fullName'),
            Validator.maxLength('#fullName', 100),
            Validator.isRequired('#password'),
            Validator.isPassword('#password'),
            Validator.isConfirmed('#checkPass', function () {
                return $('#formAddUser #password').val();
            }, "Mật khẩu nhập lại không chính xác!"),
            Validator.isSelected('#userType', 'Vui lòng chọn loại tài khoản!'),
            Validator.isSelected('#userPlace', 'Vui lòng chọn loại nơi làm việc!'),
        ],
        onSubmit: function (data) {
            addUser(data);
        }
    });
    Validator({
        form: '#formEditUser',
        formGroupSelector: '.form-group',
        errorSelector: '.form-message',
        rules: [
            Validator.isRequired('#fullName_'),
            Validator.maxLength('#fullName_', 100),
            Validator.isSelected('#userType_', 'Vui lòng chọn loại tài khoản!'),
            Validator.isSelected('#userPlace_', 'Vui lòng chọn loại nơi làm việc!'),
        ],
        onSubmit: function (data) {
            updateUser(data);
        }
    });

    Validator({
        form: '#addUserType',
        formGroupSelector: '.form-group',
        errorSelector: '.form-message',
        rules: [
            Validator.isRequired('#type'),
            Validator.maxLength('#type', 50),
            Validator.maxLength('#desc', 100),
            // Validator.isRequired('#desc'),
        ],
        onSubmit: function (data) {
            if (data.idType === "") {
                addUserType(data);
            } else {
                updateUserType(data);
            }
        }
    });

    Validator({
        form: '#addUserPlace',
        formGroupSelector: '.form-group',
        errorSelector: '.form-message',
        rules: [
            Validator.isRequired('#place'),
            Validator.isRequired('#address'),
        ],
        onSubmit: function (data) {
            if (data.idUserPlace === "") {
                addUserPlace(data);
            } else {
                updateUserPlace(data);
            }
        }
    });

})
//4. show userType
function showUserType(selector) {
    showDialog(selector);
    listUserType();
}
// 5. render list of users type
function listUserType() {
    $.post(
        "/process/getUserType.php",
        { action: 'getUserType' },
        function (response) {
            $('#userTypeList    ').html(response);
        },
        'text'
    );
}
//  6. submit addUserType
function addUserType(data) {
    $.post(
        "/process/addUserType.php",
        { type: data.type, desc: data.desc },
        function (response) {
            $('#toast').html(response);
            $('#addUserType')[0].reset();
            listUserType();
            listOptionsUserType("#formAddUser #userType");
            listOptionsUserType("#formEditUser #userType_");
        },
        'text'
    );
}
// 7. delete User Type
function deleteUserType(id, name) {
    showConfirm({
        functionName: "processDelType(" + id + ",'" + name + "')",
        message: 'Bạn có chắc chắn muốn xóa loại tài khoản "' + name + '" không?',
    });
}
function processDelType(id, name) {
    $.post(
        "/process/deleteUserType.php",
        { idUserType: id, nameUserType: name },
        function (response) {
            $('#toast').html(response);
            hideDialog('.dialog.dialogComfirm');
            listUserType();
            listOptionsUserType("#formAddUser #userType");
            listOptionsUserType("#formEditUser #userType_");
        },
        'text'
    );
}
// 8. Edit User Type
function editUserType(id, name, desc) {
    $("#addUserType #idType").val(id);
    $("#addUserType #type").val(name);
    $("#addUserType #desc").val(desc);
    $("#addUserType #submitType").html("Cập Nhật");
    $("#addUserType .formTitle").html("Cập Nhật Loại Tài Khoản");
}
function updateUserType(data) {
    $.post(
        "/process/editUserType.php",
        { idUserType: data.idType, nameUserType: data.type, descUserType: data.desc },
        function (response) {
            $('#toast').html(response);
            $('#addUserType')[0].reset();
            $("#addUserType .formTitle").html("Thêm Loại Tài Khoản");
            $("#addUserType #submitType").html("Thêm");
            listUserType();
            listOptionsUserType("#formAddUser #userType");
        },
        'text'
    );
}

// 9. Render User.
function listUsers() {
    $.post(
        "/process/getUsers.php",
        { action: 'getUsers' },
        function (response) {
            $('.userList').html(response);
        },
        'text'
    );
}
$(document).ready(function () {
    listUsers();
})

// 10. show list options user type
function listOptionsUserType(selector, val) {
    $.post(
        "/process/getOptionsUserType.php",
        { action: 'getOptionsUserType', val: val },
        function (response) {
            $(selector).html(response);
        },
        'text'
    );
}
// 11. show list options user place
function listOptionsUserPlace(selector, val) {
    $.post(
        "/process/getOptionsUserPlace.php",
        { action: 'getOptionsUserPlace', val: val },
        function (response) {
            $(selector).html(response);
        },
        'text'
    );
}
//12. show userPlace
function showUserPlace(selector) {
    showDialog(selector);
    listUserPlace();
}
// 13. render list of users place
function listUserPlace() {
    $.post(
        "/process/getUserPlace.php",
        { action: 'getUserPlace' },
        function (response) {
            $('#userPlaceList').html(response);
        },
        'text'
    );
}
//14 add User Place
function addUserPlace(data) {
    $.post(
        "/process/addUserPlace.php",
        { place: data.place, address: data.address },
        function (response) {
            $('#toast').html(response);
            $('#addUserPlace')[0].reset();
            listUserPlace();
            listOptionsUserPlace("#formAddUser #userPlace");
            listOptionsUserPlace("#formEditUser #userPlace_");
        },
        'text'
    );
}
// 15. delete User Place
function deleteUserPlace(id, name) {
    showConfirm({
        functionName: "processDelPlace(" + id + ",'" + name + "')",
        message: 'Bạn có chắc chắn muốn xóa loại tài khoản "' + name + '" không?',
    });
}
function processDelPlace(id, name) {
    $.post(
        "/process/deleteUserPlace.php",
        { id: id, name: name },
        function (response) {
            $('#toast').html(response);
            hideDialog('.dialog.dialogComfirm');
            listUserPlace();
            listOptionsUserPlace("#formAddUser #userPlace");
            listOptionsUserPlace("#formEditUser #userPlace_");
        },
        'text'
    );
}
// 16. Edit User Type
function editUserPlace(id, place, address) {
    $("#addUserPlace #idUserPlace").val(id);
    $("#addUserPlace #place").val(place);
    $("#addUserPlace #address").val(address);
    $("#addUserPlace #SubmitPlace").html("Cập Nhật");
    $("#addUserPlace .formTitle").html("Cập Nhật Điểm Giao Dịch");
}
function updateUserPlace(data) {
    $.post(
        "/process/editUserPlace.php",
        { id: data.idUserPlace, place: data.place, address: data.address },
        function (response) {
            $('#toast').html(response);
            $('#addUserPlace')[0].reset();
            $("#addUserPlace .formTitle").html("Thêm Loại Tài Khoản");
            $("#addUserPlace #SubmitPlace").html("Thêm");
            listUserPlace();
            listOptionsUserPlace("#formAddUser #userPlace");
        },
        'text'
    );
}
// 17. add user
function addUser(data) {
    $.post(
        "/process/addUser.php",
        {
            userName: data.userName,
            password: data.password,
            fullName: data.fullName,
            userType: data.userType,
            userPlace: data.userPlace,
            permiss: data.permiss,
        },
        function (response) {
            $('#toast').html(response);
            $('#formAddUser')[0].reset();
            hideDialog('#addUserDialog');
            listUsers();
        },
        'text'
    );
}
//18. Delete or lock user
function deleteUser(userName) {
    showConfirm({
        functionName: "processDelUser('" + userName + "')",
        message: 'Bạn có chắc chắn muốn xóa tài khoản "' + userName + '" không?',
    });
}
function lockUser(userName) {
    showConfirm({
        functionName: "processLockUser('" + userName + "')",
        message: 'Bạn có chắc chắn muốn khóa tài khoản "' + userName + '" không?',
    });
}
function processDelUser(userName) {
    $.post(
        "/process/deleteUser.php",
        { userName: userName },
        function (response) {
            $('#toast').html(response);
            hideDialog('.dialog.dialogComfirm');
            listUsers();
        },
        'text'
    );
}
function processLockUser(userName) {
    $.post(
        "/process/lockUser.php",
        { userName: userName },
        function (response) {
            $('#toast').html(response);
            hideDialog('.dialog.dialogComfirm');
            listUsers();
        },
        'text'
    );
}

function unlockUser(userName) {
    showConfirm({
        functionName: "processUnLockUser('" + userName + "')",
        message: 'Bạn có chắc chắn muốn mở khóa tài khoản "' + userName + '" không?',
    });
}
function processUnLockUser(userName) {
    $.post(
        "/process/lockUser.php",
        { userName: userName, action: "unlock" },
        function (response) {
            $('#toast').html(response);
            hideDialog('.dialog.dialogComfirm');
            listUsers();
        },
        'text'
    );
}
//19. Edit user
function editUser(userName, fullName, type, place) {
    showDialog('#editUserDialog');
    $('#formEditUser #userName_').val(userName);
    $('#formEditUser #fullName_').val(fullName);
    listOptionsUserType("#formEditUser #userType_", type);
    listOptionsUserPlace("#formEditUser #userPlace_", place);
    getPermissions(userName);
}
function getPermissions(userName) {
    $.post(
        "/process/getOrther.php",
        { funcName: "getPermissions", userName: userName },
        function (response) {
            var permiss = JSON.parse(response);
            $.each(permiss, function (key, value) {
                //check values when edit user
                if (value == 1) $('#formEditUser #' + key + "_").prop('checked', true);
            })

        },
        'text'
    );
}
function updateUser(data) {
    $.post(
        "/process/editUser.php",
        {
            userName: data.userName_,
            fullName: data.fullName_,
            userType: data.userType_,
            userPlace: data.userPlace_,
            permiss: data.permiss,
        },
        function (response) {
            $('#toast').html(response);
            hideDialog('#editUserDialog');
            listUsers();
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
            if(permiss['permiss1'] == 0){
                $("#action").html("");
            }
        },
        'text'
    );
}