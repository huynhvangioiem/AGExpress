/* Navigation */
$(document).ready(function () {
    //toggler menu if screen <768px
    $(".navbar-toggler").click(function () {
        var id = $(this).attr("data-target");
        if ($(id).hasClass("show")) {
            $($(id).removeClass("show"));
        } else {
            $($(id).addClass("show"));
        }
    });
    $(".nav-item").click(function () {
        $(".navbar-collapse").removeClass("show");
    })
})
