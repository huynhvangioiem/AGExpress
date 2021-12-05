$(document).ready(function () {
    $('#userListTable').DataTable({
        "language": {
            "emptyTable": "Không có dữ liệu trong bảng",
            "info": "Hiển thị _START_ - _END_ trong số _TOTAL_ danh mục",
            "infoEmpty": "Hiện thị 0 tài khoản",
            "infoFiltered": "(Đã lọc từ _MAX_ danh mục)",
            "lengthMenu": "Hiển thị _MENU_ dòng",
            "search": "Tìm kiếm:",
            "zeroRecords": "Không tìm thấy dữ liệu!",
        }
    });
});