<?php session_start();
if ( isset($_POST['userName']) && isset($_POST['password']) ) {
    if($_POST['userName']!='' && $_POST['password']!='' ) {

        $userName = $_POST['userName'];
        $password = md5($_POST['password']);
    
        include_once("connection.php");

        $resultLogin = mysqli_query($connect, "SELECT * FROM ageuser WHERE ageuser.AGEUserName = '$userName' AND ageuser.AGEUserPassword = '$password' AND AGEUserStatus != -1") or die(mysqli_connect_error($connect));
        if (mysqli_num_rows($resultLogin) == 1) {
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Đăng nhập thành công!',
                            message: '',
                            style: 'success-outline',
                            duration: 3000,
                            iconType: 'success',
                        });
                    })
                </script> 
            ";
            $rowUser = mysqli_fetch_array($resultLogin, MYSQLI_ASSOC);
            if($rowUser['AGEUserStatus']==1){
                $_SESSION["userName"] = $rowUser['AGEUserName'];
                echo '<meta http-equiv="refresh" content="0.5;URL=/" />';
            }else{
                $_SESSION["onlySetPass"] = $rowUser['AGEUserName'];
                echo '<meta http-equiv="refresh" content="0.5;URL=account.html" />';
            }
        } else {
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Đăng nhập thất bại!',
                            message: 'Thông tin đăng nhập không đúng. Vui lòng kiểm tra lại!', 
                            style: 'danger-outline',
                            duration: 10000,
                            iconType: 'warning',
                        });
                    })
                </script>
            ";
        }
    }
}
?>