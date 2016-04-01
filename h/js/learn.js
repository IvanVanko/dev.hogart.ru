$(document).load(function () {
    //$('.var-view li')
});
$(document).ready(function () {
    $('.var-view a').click(function () {
        var learnView = $(this).attr('href');
        if (!$(this).hasClass('active')){
            $('.var-view a').removeClass('active');
            $(this).addClass('active');

            $('.learn-list').slideUp(400, function () {
                $('.learn-list'+learnView).slideDown(400);
            });
            $(window).resize();
        }

        //console.log($(this).attr('href'));
        return false;
    });
});