"use strict";

var hogartApp = {};
$(function () {
  hogartApp = new HogartApp();
  hogartApp.init();

  $('header.b-header').sticky({zIndex: 999, wrapperClassName: 'header-sticky-wrapper'})

});

function toggleSearch(el) {
  $('#search').toggleClass('show');
}

function HogartApp() {
}

HogartApp.prototype.init = function () {
    this.setHandlers();
};

HogartApp.prototype.setHandlers = function () {
    var self = this;
    
    $('.btn-more').click(function () {
        var container = $("#" + $(this).attr('rel'));
        $('.more', container).animate({ height: "toggle" });
        $(this).toggleClass('opened');
    });

    $('[data-toggle="tooltip"]').tooltip();
    
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
        setTimeout(function () {
            var top = $(el).offset().top;
            $(this).css({ width: $(this).outerWidth() });
            var extTop;
            if ($(el).data('relFixedBlock') && $($(el).data('relFixedBlock')).length) {
                extTop = $($(el).data('relFixedBlock')).outerHeight();
                $(el).data("initMarginTop", $(el).css("margin-top"));
                top -= (extTop ? (extTop + 20) : 0);
                top = Math.max(0, top);
            }
            if ($(el).data('restrictParentFixedBlock') && $(el).parents($(el).data('restrictParentFixedBlock')).length) {
                var restrictionParent = $(el).parents($(el).data('restrictParentFixedBlock'));
                var bottomPos = restrictionParent.offset().top + restrictionParent.outerHeight();
                bottomPos -= (extTop ? (extTop + 20) : 0);
            }
            var blockHeight = $(el).outerHeight();
            $(window).scroll(function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > top && (bottomPos ? (scrollTop < (bottomPos - blockHeight)) : true)) {
                    $(el).addClass('sticky');
                    if (extTop) {
                        $(el).css({ "margin-top": extTop + 20 });
                    }
                } else {
                    if (extTop) {
                        $(el).css({ "margin-top": $(el).data("initMarginTop") });
                    }
                    $(el).removeClass('sticky');
                }
            });
            $(window).trigger('scroll');
        }, 0)
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

