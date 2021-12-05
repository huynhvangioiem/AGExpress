// Use Jquery 3.6
/* Dialog */
function showDialog(selector) {
    $(selector).css('display', 'flex');
}
function hideDialog(selector) {
    $(selector).css('display', 'none');
}
function showConfirm(options) {
    var html = `
        <div class="container">
            <div class="row dialogHeader">
            <div class="col-12 col-m-12 col-s-12">
                <h1 class="title">${options.title||"Cảnh Báo!"}</h1>
            </div>
            </div>
            <div class="row dialogContent">
            <div class="col-12 col-m-12 col-s-12">
                <p class="message">${options.message||"Bạn có chắc chắn thực hiện tác vụ này không?"}</p>
            </div>
            </div>
            <div class="row dialogFooter">
            <button class="btn btn-warning" id="actionCancel" type="button">${options.actionCancel||"Không"}</button>
            <button class="btn btn-info" onclick="${options.functionName}" id="actionOK" type="button">${options.actionOk||"Có"}</button>
            </div>
        </div>
    `;
    $('.dialog.dialogComfirm').html(html);
    showDialog('.dialog.dialogComfirm');
    $('#actionCancel').click(function(){
        hideDialog('.dialog.dialogComfirm');
    })
}