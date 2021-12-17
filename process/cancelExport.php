<?php
  if( isset($_POST['id']) ){ //check isset data
    if( //check input value 
      is_numeric($_POST['id'])  && $_POST['id']   != ''
    ){ 
      include_once("connection.php");
      try { //
        mysqli_query($connect, "UPDATE `ageexport` SET `AGEExportStatus` = '-1' WHERE `ageexport`.`AGEExportID` ='".$_POST['id']."'") or die(mysqli_connect_error($connect));
        $get = mysqli_query($connect, "SELECT a.AGEBoL, c.AGEPlace FROM `ageexportdetail` a JOIN ageexport b ON a.AGEExport = b.AGEExportID JOIN ageuser c ON b.AGEUser = c.AGEUserName WHERE `AGEExport` = '".$_POST['id']."'") or die(mysqli_connect_error($connect));
        while ($data = mysqli_fetch_array($get, MYSQLI_ASSOC)) { 
          mysqli_query($connect, "UPDATE `agebol` SET `AGEBoLStatus` = ".$data['AGEPlace']." WHERE `agebol`.`AGEBoLID` = '".$data['AGEBoL']."'") or die(mysqli_connect_error($connect));
          mysqli_query($connect, "DELETE FROM `ageexportdetail` WHERE `ageexportdetail`.`AGEBoL` = '".$data['AGEBoL']."' AND `ageexportdetail`.`AGEExport` ='".$_POST['id']."'") or die(mysqli_connect_error($connect));
        }
        echo "
          <script>
            $(document).ready(() => {
              toast({
                title: 'Thành Công!',
                message: 'Phiếu xuất đã được hủy',
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