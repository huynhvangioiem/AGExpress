<?php
if(isset($_POST['userName'])){
    if(is_string($_POST['userName'])&& $_POST['userName']!=''){
        include_once("connection.php");
        try {
            $get = mysqli_query($connect, "SELECT ageusertype.AGEUserTypeName FROM ageuser JOIN ageusertype ON ageusertype.AGEUserTypeID = ageuser.AGEUserType  WHERE ageuser.AGEUserName = '".$_POST['userName']."'") or die(mysqli_connect_error($connect));
            $data=mysqli_fetch_array($get, MYSQLI_ASSOC);
            if($data['AGEUserTypeName']!=="Admin"){
                try {
                    mysqli_query($connect, "UPDATE `ageuser` SET `AGEUserStatus`=-1 WHERE `AGEUserName` = '".$_POST['userName']."'") or die(mysqli_connect_error($connect));
                    echo "
                        <script>
                            $(document).ready(() => {
                                toast({
                                    title: 'Thành Công!',
                                    message: 'Đã vô hiệu tài khoản ".$_POST["userName"].".',
                                    style: 'success-outline',
                                    duration: 5000,
                                    iconType: 'success',
                                });
                            })
                        </script> 
                    ";
                } catch (\Throwable $th) {
                    echo "
                        <script>
                            $(document).ready(() => {
                                toast({
                                    title: 'Thất Bại!',
                                    message: 'Đã có lỗi xảy ra. Vui lòng thử lại!',
                                    style: 'danger-outline',
                                    duration: 5000,
                                    iconType: 'danger',
                                });
                            })
                        </script> 
                    ";
                }
            }else{
                echo "
                    <script>
                        $(document).ready(() => {
                            toast({
                                title: 'Thất Bại!',
                                message: 'Không thể vô hiệu tài khoản này!',
                                style: 'danger-outline',
                                duration: 5000,
                                iconType: 'danger',
                            });
                        })
                    </script> 
                ";
            }
        } catch (\Throwable $th) {
            echo "
                <script>
                    $(document).ready(() => {
                        toast({
                            title: 'Thất Bại!',
                            message: 'Đã có lỗi xảy ra. Vui lòng thử lại!',
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