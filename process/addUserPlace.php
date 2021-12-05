<?php
if (isset($_POST['place']) && isset($_POST['address'])) {
    if($_POST['place']&&$_POST['address']!=''){
        $userPlace = $_POST['place'];
        $address = $_POST['address'];
        include_once("connection.php");
        try {
            $get = mysqli_query($connect, "SELECT * FROM `ageplace` WHERE `AGEPlaceName`= '$userPlace'") or die(mysqli_connect_error($connect));
            if (mysqli_num_rows($get) == 0) {
                try {
                    mysqli_query($connect, "INSERT INTO `ageplace`(`AGEPlaceName`, `AGEPlaceAddress`) VALUES ('$userPlace','$address')") or die(mysqli_connect_error($connect));
                    echo "
                        <script>
                            $(document).ready(() => {
                                toast({
                                    title: 'Thành Công!',
                                    message: 'Đã thêm điểm GD $userPlace.',
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
                                message: 'Điểm giao dịch \"$userPlace\" đã tồn tại.',
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