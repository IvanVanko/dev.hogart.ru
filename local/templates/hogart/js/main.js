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

    $('a[href*="#"]:not([href="#"])').click(function(event) {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var self = this;
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000, function () {
                    document.location.hash = self.hash;
                });
                event.preventDefault();
            }
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
        $(window).scroll(function() {
            if ($(this).scrollTop() > top ) {
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
        });
    });
};

