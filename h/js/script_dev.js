$ (document).on('ready', function() {

    $('.js-filter-mobile').on('click', function() {
        console.log(2323);
        $('.filter-mobile').addClass('active');
        $("body").css({'overflow': 'hidden', 'position': 'fixed', 'width': '100vw'});
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".filter-mobile, .js-filter-mobile").length) return;
        $('.filter-mobile').removeClass('active');
        $("body").attr({'style':''});
        event.stopPropagation();
    });
    $(".filter-mobile").mCustomScrollbar();
});

$ (document).on('ready', function() {

    $('.header-mobile__menu').on('click', function() {
        $('.hamburger-mobile').addClass('active');
        $('.perspective').addClass('active');
        $('.hamburger-mobile__close').addClass('active');
        $('.hamburger-mobile__content').addClass('active');     
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".hamburger-mobile, .header-mobile__menu").length) return;
        $('.hamburger-mobile').removeClass('active');
        $('.hamburger-mobile__close').removeClass('active');
        $('.perspective').removeClass('active');
        $('.hamburger-mobile__content').removeClass('active');
        event.stopPropagation();
    });

    /*$(".hamburger-mobile").mCustomScrollbar();*/
});