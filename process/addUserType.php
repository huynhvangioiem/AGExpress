<?php
if (isset($_POST['type']) && isset($_POST['desc'])) {
    if($_POST['type'] !=''){
        $userType = $_POST['type'];
        $desctionType = $_POST['desc'];
        include_once("connection.php");
        try {
            $get = mysqli_query($connect, "SELECT * FROM `ageusertype` WHERE `AGEUserTypeName`= '$userType'") or die(mysqli_connect_error($connect));
            if (mysqli_num_rows($get) == 0) {
                try {
                    mysqli_query($connect, "INSERT INTO `ageusertype` (`AGEUserTypeName`, `AGEUserTypeDescription`)  VALUES ('$userType', '$desctionType');") or die(mysqli_connect_error($connect));
                    echo "
                        <script>
                            $(document).ready(() => {
                                toast({
                                    title: 'Thành Công!',
                                    message: 'Loại tài khoản $userType đã được thêm thành công.',
                                    style: 'success-outline',
                                    duration: 5000,
                                    iconType: 'success',
                                });
                            })
                        </script> 
                    ";
                } catch (\Throwable $th) {}
            }else{
                echo "
                    <script>
                        $(document).ready(() => {
                            toast({
                                title: 'Thất Bại!',
                                message: 'Loại tài khoản \"$userType\" đã tồn tại.',
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
                            message: 'Đã có lỗi. Vui lòng kiểm tra lại.',
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