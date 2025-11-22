const flashDataFailed = $('.flash-data-failed').data('flash');

if (flashDataFailed) {
    Swal({
        title: 'Failed',
        text: flashDataFailed,
        type: 'error'
    })
}

$(function(){ 
    var navMain = $(".navbar-collapse");
    navMain.on("click", "a:not([data-toggle])", null, function () {
        navMain.collapse('hide');
    });
});