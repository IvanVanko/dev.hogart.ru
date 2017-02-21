$ (document).on('ready', function() {

    $('.js-filter-mobile').on('click', function() {
        $('.filter-mobile').toggleClass('active');
        $('.filter-mobile__link').toggleClass('active');
        $('body').toggleClass('body-mobile');
        return false;
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".filter-mobile, .js-filter-mobile").length) return;
        $('.filter-mobile').removeClass('active');
        $('.filter-mobile__link').removeClass('active');
        $('body').removeClass('body-mobile');
        event.stopPropagation();
    });
    $(".filter-mobile").mCustomScrollbar();
});

$ (document).on('ready', function() {

    $('.header-mobile__menu').on('click', function() {
        $('.perspective').addClass('active');     
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".hamburger-mobile, .header-mobile__menu").length) return;
        $('.perspective').removeClass('active');
        event.stopPropagation();
    });

    /*$(".hamburger-mobile").mCustomScrollbar();*/
});

$ (document).on('ready', function() {

    $('.js-help').on('click', function() {
        $('.footer-menu').toggleClass('active');  
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".js-help, .footer-menu").length) return;
        $('.footer-menu').removeClass('active');
        event.stopPropagation();
    });
});

$ (document).on('ready', function() {

    $('.js-help-main').on('click', function() {
        $('.footer-menu-main').toggleClass('active');  
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".js-help-main, .footer-menu-main").length) return;
        $('.footer-menu-main').removeClass('active');
        event.stopPropagation();
    });
});

/*$ (document).on('ready', function() {

    $('.header-mobile__search-label').on('click', function() {
        $('.header-mobile__search-input').toggleClass('active'); 
        return false; 
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".header-mobile__search-label, .header-mobile__search-input").length) return;
        $('.header-mobile__search-input').removeClass('active');
        event.stopPropagation();
    });
}); */ 

$ (document).on('ready', function() {

    $('.js-filter-stock-mobile').on('click', function() {
        $('.filter-stock').toggleClass('active');
        $('.filter-stock__link').toggleClass('active');
        $('body').toggleClass('body-mobile');
        return false;
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".filter-stock, .js-filter-stock-mobile").length) return;
        $('.filter-stock').removeClass('active');
        $('.filter-stock__link').removeClass('active');
        $('body').removeClass('body-mobile');
        event.stopPropagation();
    });
    $(".filter-stock").mCustomScrollbar();
});        