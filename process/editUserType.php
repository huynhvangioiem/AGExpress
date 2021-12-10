<?php
if(isset($_POST['idUserType']) && isset($_POST['nameUserType']) && isset($_POST['descUserType'])){ // check is set data
    if(is_numeric($_POST['idUserType']) && is_string($_POST['nameUserType']) && $_POST['nameUserType']!=""){ // check input value
        include_once("connection.php");
        try { // try to update type
            mysqli_query($connect, "UPDATE `ageusertype` SET `AGEUserTypeName`='".$_POST['nameUserType']."',`AGEUserTypeDescription`='".$_POST['descUserType']."' WHERE `AGEUserTypeID` =".$_POST['idUserType']) or die(mysqli_connect_error($connect));
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thành Công!',
                            message: 'Đã cập nhật loại tài khoản thành công.',
                            style: 'success-outline',
                            duration: 5000,
                            iconType: 'success',
                        });
                    })
                </script> 
            ";
        } catch (\Throwable $th) { // if wrong, aler error message
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thất Bại!',
                            message: 'Có lỗi xãy ra. Vui lòng thử lại!',
                            style: 'danger-outline',
                            duration: 5000,
                            iconType: 'danger',
                        });
                    })
                </script> 
            ";
        }
    }
}
?>