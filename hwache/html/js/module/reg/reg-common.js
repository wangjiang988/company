define(function (require) {

    $(".form input").bind("keydown", function () {
        $(this).next().addClass("hide")
    })
    $(function () { 
        $(".location").hide();
    });
});