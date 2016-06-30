"use strict";

var hogartApp = {};
$(function () {
    hogartApp = new HogartApp();
    hogartApp.init();
});

function HogartApp() {
}

HogartApp.prototype.init = function () {
    this.setHandlers();
};

HogartApp.prototype.setHandlers = function () {
    var self = this;
    
    $('.eventRegistrationForm').on('forms.submit.success', function(event, data){
        if(data.redirect) {
            var link = document.createElement('A');
            link.target = '_blank';
            link.style = 'display: none;';
            link.href = data.redirect;
            document.body.appendChild(link);
            link.click();
        }
    });
    
    $(window).on('popstate', function (event) {
        event.preventDefault();
        var target = $(window.location.hash);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top
            }, 800);
            return false;
        }
    });

    $('a[href*="#"]:not([href="#"]):not([role="tab"]):not([data-toggle])').click(function(event) {
        if (window.location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && window.location.hostname == this.hostname) {
            event.preventDefault();
            var target = $(this.hash);
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 800);
                history.pushState(null, null, this.hash);
            }
            return false;
        }
    });

    $(".fixed-block").each(function (i, el) {
        var top = $(el).offset().top;
        var extTop;
        if ($(el).data('rel-fixed-block') && $($(el).data('rel-fixed-block')).length) {
            extTop = $($(el).data('rel-fixed-block')).outerHeight();
            $(el).data("init-margin-top", $(el).css("margin-top"));
            top -= extTop;
            top = Math.max(0, top);
        }
        var checkScroll = function () {
            if ($(window).scrollTop() > top - (extTop ? 20 : 0) ) {
                $(el).addClass('sticky');
                if (extTop) {
                    $(el).css({ "margin-top": extTop + 20 });
                }
            } else {
                if (extTop) {
                    $(el).css({ "margin-top": $(el).data("init-margin-top") });
                }
                $(el).removeClass('sticky');
            }
        };
        $(window).scroll(checkScroll);
        checkScroll();
    });

    $('#scroll-up').click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 800);
    });
    function checkScrollUp () {
        if ($(window).scrollTop() > 1 ) {
            $('#scroll-up').css({ visibility: "visible"})
        } else {
            $('#scroll-up').css({ visibility: "hidden"})
        }
    }
    $(window).scroll(checkScrollUp);
    checkScrollUp ();
};

