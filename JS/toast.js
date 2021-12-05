// Use Jquery 3.6
/* Toasts */
// Function toast - main function for toast messages, you will call it to execute!  
function toast({ title = "", message = "", style = "", duration = 10000, iconType = "" }) {
    toastMsg({ title, message, style, duration, iconType }); //create and remove a toast message
    // downToast(); //create slide down effect when add new toast more

    // function toastMsg - create and remove a toast message
    function toastMsg({ title, message, style, duration, iconType }) {
        const main = $("#toast").get(0);
        if (main) {
            // icons list style
            const icons = {
                success: "fas fa-check-circle",
                info: "fas fa-info-circle",
                warning: "fas fa-exclamation-circle",
                danger: "fas fa-exclamation-circle"
            };

            // set icon style for toast message
            var icon;
            if (iconType === ""){
                if(style.length-8 >=4){
                    icon = icons[style.substr(0,style.length-8)];
                    // console.log(style.substr(0,style.length-8));
                }else icon = icons[style];
            }
            else icon = icons[iconType];
            //delay time (s) 
            const delay = (duration / 1000).toFixed(2);
            // Create a toast message
            const toast = $(
                `<div>
                    <div class="toast_icon"><i class="${icon}"></i></div>
                    <div class="toast_body">
                        <h3 class="toast_title">${title}</h3>
                        <p class="toast_msg">${message}</p>
                    </div>
                    <div class="toast_close"><i class="far fa-times-circle"></i></div>
                </div>`
            )   .addClass("toast toast-" + style)
                .css("animation", "slideInLeft ease 0.5s, fadeOut linear 1s " + delay + "s forwards")
                .appendTo("#toast").get(0);

            // Auto remove toast
            const autoRemoveId = setTimeout(function () {
                main.removeChild(toast);
            }, duration + 1000);
            // Remove toast when clicked
            toast.onclick = function (e) {
                if (e.target.closest(".toast_close")) {
                    main.removeChild(toast);
                    clearTimeout(autoRemoveId);
                }
            };
        }
    }
    //function downToats - function create slide down effect when add new toast more
    function downToast() {
        const main = $("#toast").get(0);
        if (main != null && main.childElementCount > 1) {
            var pos = - main.lastChild.offsetHeight;
            var down = null;
            clearInterval(down);
            setTopForAll(pos);
            down = setInterval(frame, 5);
            function frame() {
                if (pos === 0) {
                    clearInterval(down);
                } else {
                    pos++;
                    setTopForAll(pos);
                }
            }
            // Set top for .toast[i], i=0 to childElementCount - 1
            function setTopForAll(pos) {
                for (i = 0; i < main.childElementCount - 1; i++) {
                    $(".toast").get(i).style.top = pos + 'px';
                }
            }
        }

    }

}
