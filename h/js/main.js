var app = {};
app.init = function () {
    return this;
};
houdini.init({
    callbackAfter: function (toggle) {
        if ($(toggle).closest('.js-slider-similar').prop('slider')) {
            $(toggle).closest('.js-slider-similar').prop('slider').reloadSlider(); //магическое проперти
        }
        if ($(toggle).is(".active")) {
            $(toggle).find('span').text($(toggle).data('active-label'));
        }
        else {
            $(toggle).find('span').text($(toggle).data('hidden-label'));
        }
    }
});
document.merged_forms_cache = [];

Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

app.initFullHeight = function () {
    setTimeout(function () {
        var inner_height = 0;
        $(".main-container .container-inner > .inner").each(function () {
            inner_height += $(this).outerHeight();
        });

        var window_height = $(window).outerHeight() - 55;


        $(".index-page .blur-main").height($(window).outerHeight());
        $(".index-page .wrapper").height($(window).outerHeight());

        // console.clear();
        // console.log("Inner: " + inner_height);
        // console.log("Window: " + window_height);
        /* Murdoc:
         * Решение для футера с маленькой высотой контентной области
         * Скролинг правого блока при маленькой высоте контентной области
         */

        $("aside.sidebar").css({
            overflow: "auto"
        });

        if (inner_height <= window_height - 55) {
            $(".learn-head-link").parent().css({
                position: "absolute",
                bottom: "55px",
                //width: "100%",
                right: "0px",
                'margin-right': "330px",
                'margin-left': "235px",
                left: "0px",
                padding: "0px"
            }).addClass("right");
        }
        else {
            var window1024 = ($(window).outerWidth() < 1024) ? 1024 : $(window).outerWidth();
            if ($("aside.sidebar").length) {
                $(".learn-head-link").parent().css({
                    position: "static",
                    bottom: "auto",
                    width: window1024 - 565,
                    'margin-right': "0px",
                    'margin-left': "0px",
                }).removeClass("right");

            }
            else {
                $(".learn-head-link").parent().css({
                    position: "static",
                    bottom: "auto",
                    width: window1024 - 235,
                    'margin-right': "0px",
                    'margin-left': "0px",
                }).removeClass("right");
            }
        }

        if (inner_height <= window_height - 55) {
            $(".main-container footer:not(.blockquote)").addClass("right");
            if (!$('.presentation-main-page').length) {
                $("footer:not(.blockquote) .p_logo").css({
                    'position': 'relative',
                    'right': '330px'
                });
                $("footer:not(.blockquote) span").css({
                    'position': 'relative',
                    'left': '220px'
                });
            }

        }
        else {
            var window1024 = ($(window).outerWidth() < 1024) ? 1024 : $(window).outerWidth();
            if ($("aside.sidebar").length) {
                $(".main-container footer:not(.blockquote)").removeClass("right");

                $(".learn-head-link").parent().css({
                    position: "static",
                    bottom: "auto",
                    width: window1024 - 565,
                    'margin-right': "0px",
                    'margin-left': "0px",
                }).removeClass("right");

            }
            else {
                $(".main-container footer:not(.blockquote)").css({
                    position: "static",
                    bottom: "auto",
                    width: window1024 - 235
                }).removeClass("right");

                $(".learn-head-link").parent().css({
                    position: "static",
                    bottom: "auto",
                    width: window1024 - 235,
                    'margin-right': "0px",
                    'margin-left': "0px",
                }).removeClass("right");
            }
            if (!$('.presentation-main-page').length) {
                $("footer:not(.blockquote) .p_logo").css({
                    'position': '',
                    'right': ''
                });
                $("footer:not(.blockquote) span").css({
                    'position': '',
                    'left': ''
                });
            }
            //
            //$("aside.sidebar").css({overflow: "hidden"});
            //
            ////MURDOC: БЛЯ! :-(
            //if ($("aside.sidebar .js-paralax-item").outerHeight() > $(window).outerHeight()) {
            //    $("aside.sidebar").height($("aside.sidebar .js-paralax-item").outerHeight() + 25);
            //} else {
            //    $("aside.sidebar").height($(window).outerHeight() - 55);
            //}
        }
    }, 10 / 10);

    return this;
};

app.initViewIcoNextBack = function () {
    new ResizeSensor($('aside.sidebar > .js-paralax-item'), function () {
        if ($('.scroller_icon').length) {
            var $item = $('.scroller_icon');
        }
        else {
            var $item = $('<div class="scroller_icon"></div>');
            $('aside.sidebar').append($item);
        }

        var height = $('aside.sidebar > .js-paralax-item').outerHeight(),
            winH = $(window).outerHeight() - 50;

        if (height > winH) {
            $('.scroller_icon').show();
        }
        else {
            $('.scroller_icon').hide();
        }
    });
};

app.initFullHeightParent = function () {
    setTimeout(function () {
        $('.js-fp').each(function () {
            if ($(this).parent().height() && $(this).height() < $(this).parent().height()) {
                $(this).height($(window).outerHeight());
            }
        });
    }, 20 / 10);

    return this;
};
app.initFullDocument = function () {
    return this;
};
app.initMenu = function () {
    if ($('#main-menu-trigger').filter(':checked').length) {
        $('#main-menu-trigger').prop('checked', false);
    }
    if ($.trim($('.blur-main .main-sidebar .present-cnt .content').text()) == '')
    {
        $('.blur-main .main-sidebar').addClass('empty').closest('.blur-main').addClass('js-fixed-block').attr('data-fixed', 'top').
        find('.js-fixed-block').removeClass('js-fixed-block');
    }
    var initWidth = $('.blur-main .main-sidebar').width();
    var ot = $('.blur-main .main-sidebar').hasClass('empty') ? 0 : 65;
    //При изменения состояния чеккбокса ответственного за состояние меню
    $('#main-menu-trigger').change(function () {
        //проверяем, если выделен чекбокс, отрабатывам анимацию появления меню
        if ($(this).filter(':checked').length) {
            $('.blur-main .main-sidebar').css({
                'width': ot ? '65px' : 0,
                //двигаем элемент вправо и уменьшаем высоту
                'margin-left': initWidth - ot + 'px'
            }).find('.wrap').css({
                //двигаем врапер влево тем самым создавая эффект неподвижности блока и перекрывая его содержимое
                'margin-left': -initWidth + ot + 'px'
            });
            $('.blur-main .hide-block .hidden-menu').css({
                'left': 0
            });
        }
        else {
            $('.blur-main .main-sidebar').css({
                'width': initWidth + 'px',
                'margin-left': 0
            }).find('.wrap').css({
                'margin-left': 0
            });
            $('.blur-main .hide-block .hidden-menu').css({
                'left': '-235px'
            });

        }
    });
    return this;
};
app.initFixedBlock = function () {
    $('.js-fixed-block').each(function () {
        var nav = $(this).attr('data-fixed'),
            $obj = $(this),
            winh, tmp;
        if (!nav) {
            nav = 'top';
        }
        var funcs = function () {
                winh = $(document).height();
                tmp = {
                    left: $obj.css('left'),
                    top: $obj.css('top')
                };
            },
            funce = function () {
                if ($(document).height() > winh) {
                    $obj.css({
                        'left': tmp.left,
                        'top': tmp.top
                    });
                }
            };
        if (nav == 'left') {
            $(window).scroll(function () {
                funcs();
                $obj.css({
                    'left': $(window).scrollLeft()
                });
                funce();
            });
        }
        if (nav == 'top') {
            $(window).scroll(function () {
                funcs();
                $obj.css({
                    'top': $(window).scrollTop()
                });
                funce();
            });
        }
        if (nav == 'any') {
            $(window).scroll(function () {
                funcs();
                $obj.css({
                    'left': $(window).scrollLeft(),
                    'top': $(window).scrollTop()
                });
                funce();
            });
        }
    });
    return this;
};
app.initDatepicker = function () {
    $('.js-datepicker, .js-datepicker-hogart').each(function () {
        var dates = {};
        var id = $(this).attr('data-datepicker');
        $(id).find('li').each(function () {
            dates[new Date($(this).attr('data-date')).getTime()] = $(this).html();
        });
        $(this).datepicker({
            showOtherMonths: true,
            beforeShowDay: function (date) {
                var hlText = dates[date.getTime()];
                return (hlText) ? [true, "js-tooltip-hover", hlText] : [true, '', ''];
            }
        });

        if ($(this).data("lang")) {
            $(this).datepicker("option", $.datepicker.regional[ $(this).data("lang") ] );
        }

        $(this).click(function () {
            app.initCalendarTooltip();
        });
        $('td', this).unbind('click');
    });
    return this;
};
app.initSideNews = function () {
    $('.side-news-list').bxSlider({
        pager: false,
        nextText: '',
        prevText: '',
        hideControlOnEnd: true,
        nextSelector: $('.side-news-list + .control>.next'),
        prevSelector: $('.side-news-list + .control>.prev')

    });
    return this;
};
/*Простой конвектор свойств*/
app.converter_properties = function ($obj, $objTo, property, propertyTo) {
    if (property == 'width') {
        var pr = $obj.width();
        $objTo.css(propertyTo, pr);
    }
};
app.initCalendarTooltip = function () {
    var hovered = false;
    var func_position = function () {
        $('.js-tooltip-hover').each(function () {
            $(this).attr('data-position', $(this).offset().top + ',' + $(this).offset().left);
        });
    };
    func_position();
    $('.js-tooltip').css({
        'opacity': 0
    });
    $('.js-tooltip-hover').hover(function () {
        func_position();
        var winHeight = $(document).height();
        var text = $(this).attr('title'),
            date = $(this).find('a').text();
        $('.js-tooltip').find('.time').text(date);
        //$('.js-tooltip').find('.text').text(text);
        $('.js-tooltip').find('.text').html(text);
        var offs = $(this).attr('data-position').split(',');
        $('.js-tooltip').css({
            left: offs[1] + 'px',
            top: offs[0] + 'px'
        });

        if ($('.js-tooltip').outerHeight() + parseInt($('.js-tooltip').css('top')) > $(window).height() + $(window).scrollTop()) {
            $('.js-tooltip').css({
                top: offs[0] - $('.js-tooltip').outerHeight() + 40 + 'px'
            });
        }
        if ($('.js-tooltip').outerWidth() + parseInt($('.js-tooltip').css('left')) > $(window).width() + $(window).scrollLeft()) {
            $('.js-tooltip').css({
                left: offs[1] - $('.js-tooltip').outerWidth() + 40 + 'px'
            });
        }

        $('.js-tooltip').stop().animate({
            'opacity': 1
        }, 500);
    }, function () {
        var func = function ($this) {
            $($this).stop().animate({
                'opacity': 0
            }, 100, function () {
                $($this).css({
                    left: '-1000px',
                    top: '-1000px'
                });
            });
        };
        $('.js-tooltip').hover(null, function () {
            func(this);
        });
        $('.js-tooltip .close').click(function () {
            func($(this).parent());
        });
    });

    return this;
};
app.initIsSidebar = function () {
    $('.container-inner').each(function () {
        if ($(this).find('.sidebar:not(.sidebar-popup)').length) {
            $('.inner', this).removeClass('no-full');
            $(this).children('.inner,.breadcrumbs').addClass('no-full');
        }
    });
    return this;
};
app.initCompanySlider = function () {
    /*setTimeout(function () {
     $('.js-company-slider').each(function () {
     var width = $(this).width() / 6;
     $(this).bxSlider({
     minSlides: 6,
     maxSlides: 6,
     slideMargin: 22,
     slideWidth: width - 22,
     pager: false,
     nextText: '',
     prevText: '',
     nextSelector: $('#js-control-company').find('.next'),
     prevSelector: $('#js-control-company').find('.prev'),
     infiniteLoop: false
     });
     });
     }, 40);*/

    return this;
};
app.initCompanySlider = function () {
    setTimeout(function () {
        $('.js-itegr-slider').each(function () {
            var width = $(this).width() / 3;
            $(this).bxSlider({
                minSlides: 1,
                maxSlides: 3,
                slideMargin: 22,
                slideWidth: width,
                pager: false,
                nextText: '',
                prevText: '',
                nextSelector: $('#js-control-itegr').find('.next'),
                prevSelector: $('#js-control-itegr').find('.prev'),
                infiniteLoop: false
            });
        });
        $('.js-itegr-slider2').each(function () {
            var width = ($(this).width() / 3) - 22;
            $(this).bxSlider({
                minSlides: 1,
                maxSlides: 3,
                slideMargin: 22,
                slideWidth: width,
                pager: false,
                nextText: '',
                prevText: '',
                nextSelector: $('#js-control-itegr2').find('.next'),
                prevSelector: $('#js-control-itegr2').find('.prev'),
                infiniteLoop: false
            });
        });
    }, 40 / 10);
    return this;
};
app.initParalaxScroll = function () {
};
app.accordion = function () {
    $('.js-accordion').each(function () {
        var tr = $(this).attr('data-accordion');
        if ($(this).parent().index() != 0) {
            $(tr).slideUp().css({
                'overflow': 'hidden'
            });
        }
        else {
            $(this).parent().addClass('active');
        }
        $(this).click(function (e) {
            $('.list-href li').not('.item').slideUp();
            $('.list-href li.item').removeClass('active');
            e.preventDefault();
            if ($(tr).is(':visible')) {
                $(tr).slideUp();
                $(this).parent().removeClass('active');
                $(this).removeClass('active');
            }
            else {
                $(this).parent().addClass('active');
                $(tr).slideDown();
                $(this).addClass('active');
            }
        });
    });
    return this;
};
app.initPopUpImg = function () {
    var $pop = $('<div class="popup-img-cnt">' +
        '<div class="inner-popup-img">' +
        '<div class="prev"></div>' +
        '<div class="content-img"></div>' +
        '<div class="next"></div>' +
        '<div class="close"></div>' +
        '</div>' +
        '</div>');
    $pop.css({
        'opacity': 0
    }).hide();
    $('body').append($pop);
    var nextItem = null,
        prevItem = null;
    var $trigger = $('.js-popup-open-img');
    $trigger.each(function () {
        var $popup_image = $(this);
        if ($(this).hasClass()) {
        }
        var $pr = $(this).parent().hasClass('img-wrap') ? $(this).parent() : $(this);
        $pr.click(function () {
            var $obj = $(this).is('img') ? $(this) : $(this).find('img').first();
            var src = $obj.attr('src'),
                srcBig = $obj.attr('data-big-img'),
                srcBigVideo = $obj.attr('data-big-video'),
                group = $obj.attr('data-group'),
                srcIf = (srcBig != undefined) ? srcBig : src;
            //$img = $pop.find('.content-img').html('<img src="' + src + '">');
            if (srcBigVideo != undefined) {
                var $img = $pop.find('.content-img').html('<iframe width="100%" height="100%"' +
                    'src="https://www.youtube.com/embed/' + srcBigVideo + '?rel=0&amp;showinfo=0"' +
                    'frameborder="0" allowfullscreen></iframe>');
            }
            else {
                var $img = $pop.find('.content-img').html('<img src="' + srcIf + '">');
            }

            if ($popup_image.attr('title')) {
                console.log($popup_image.attr('title'));
                var html = $img.html();
                html += "<div class=\"slider-comment\">" + $popup_image.attr('title') + "</div>";
                $pop.find('.content-img').html(html);
            }

            //console.log(src, ' | ', srcBig, ' | ', srcIf);
            if (group) {
                //var array = $('.js-popup-open-img[data-group=' + group + ']');
                var array = $('[data-group=' + group + ']');
                //console.log("Массив элементов");
                //console.log(array);
                $pop.find('.prev, .next').show();
                var index = array.index($obj);
                nextItem = array.eq(index + 1);
                prevItem = array.eq(index - 1);
                //console.log("Индекс элементов");
                //console.log(index);
                if (index + 1 == array.length) {
                    $pop.find('.next').hide();
                }
                if (index == 0) {
                    $pop.find('.prev').hide();
                }
            }
            else {
                nextItem = prevItem = null;
                $pop.find('.prev, .next').hide();
            }

            $pop.show();
            //$pop.find('.inner-popup-img').css({'margin-top': ($(window).height() - $img.height()) / 2 + 'px'});
            $pop.animate({
                'opacity': 1
            }, 300);
        });
    });


    if ($('.js-popup-open-video').length > 0) {
        var $trigger = $('.js-popup-open-video');
        $trigger.each(function () {
            $(this).click(function () {
                var src = $(this).find('iframe').clone();
                //sourceImg = $(this).find('img');
                //src.width(sourceImg.width());
                //src.height(sourceImg.height());
                var $img = $pop.find('.content-img').html(src);

                nextItem = prevItem = null;
                $pop.find('.prev, .next').hide();
                $pop.show();

                $pop.animate({
                    'opacity': 1
                }, 300);
            });

        });
    }

    $pop.find('.close').click(function () {
        $pop.animate({
            'opacity': 0
        }, 300, function () {
            $pop.hide();
        });
        $(this).parent().find('iframe').remove();

    });
    $(document).on('keyup', function (e) {
        $pop.animate({
            'opacity': 0
        }, 300, function () {
            $pop.hide();
        });
        $(this).parent().find('iframe').remove();
    });

    $pop.click(function (e) {
        // if (e.target.getAttribute('class') == 'popup-img-cnt') {
        //     e.preventDefault();
        //     $pop.animate({'opacity': 0}, 300, function () {
        //         $pop.hide();
        //     });
        //     $(this).parent().find('iframe').remove();
        // }
    });

    $pop.find('.next').click(function () {

        $pop.animate({
            'opacity': 0
        }, 300, function () {
            $pop.hide();
            nextItem.click();
        });
    });


    $pop.find('.prev').click(function () {
        $pop.animate({
            'opacity': 0
        }, 300, function () {
            $pop.hide();
            prevItem.click();
        });
    });

    return this;
}
app.equalHeight = function () {
    var arr = [];
    $('.js-equal-height').each(function () {
        if ($.inArray($(this).attr('data-group'), arr) == -1) {
            arr[arr.length] = $(this).attr('data-group');
        }
    });
    for (var i = 0; i < arr.length; i++) {
        var maxHeight = 0;
        $(".js-equal-height[data-group=" + arr[i] + "]").each(function () {
            if ($(this).height() > maxHeight) {
                maxHeight = $(this).height();
            }
        });
        $(".js-equal-height[data-group=" + arr[i] + "]").height(maxHeight);
    }
    return this;
};
app.initTabs = function () {
    var app_t = this;
    $('.js-tabs-list').each(function () {
        $('.js-tab-item').hide();
        var $this = $(this);
        $(this).find('.js-tab-trigger').click(function (e) {
            //e.preventDefault();
            if (!$(this).hasClass('active')) {
                $('.js-tab-item').hide();
                var href = $(this).attr('href');
                $this.find('.js-tab-trigger').removeClass('active');
                $(this).addClass('active');
                if (href == "") {
                    $this.find('.js-tab-trigger[data-group=' + $(this).attr('data-show') + ']').each(function () {
                        var href = $(this).attr('href');
                        $('.js-tab-item[data-id=' + href + ']').slideDown('fast');
                        $('.js-tab-item').find('.way-scheme').slideUp('fast');
                        $('.js-tab-item').find('.video-way-file').slideUp('fast');
                        $('.js-tab-item').find('.video-way-file').find('iframe').remove();
                    });
                }
                else {
                    $('.js-tab-item[data-id=' + href + ']').slideDown('fast');
                    $('.js-tab-item').find('.way-scheme').slideUp('fast');
                    $('.js-tab-item').find('.video-way-file').slideUp('fast');
                    $('.js-tab-item').find('.video-way-file').find('iframe').remove();
                }
            }
        });
        if (window.location.hash) {
            $(this).find('.js-tab-trigger[href=' + window.location.hash + ']').click();
        }
        else {
            $(this).find('.js-tab-trigger').eq(0).click();
        }

        $('.no-full .js-tab-item').each(function () {
            var cur_hash = $(this).attr('data-id');
            $(this).find('.pagination a').each(function () {
                $(this).attr('href', $(this).attr('href') + cur_hash);
            });
        });
    });
    return this;
};
app.menuSelect = function () {
    $('.js-menu-select').each(function () {
        var $trigger = $(this).find('.sub-menu').prev('a');
        $trigger.next('.sub-menu').css({
            'max-height': 0
        });
        $trigger.click(function (e) {
            e.preventDefault();
            if ($(this).hasClass('active')) {
                $(this).next('.sub-menu').animate({
                    'max-height': '0'
                }, 100);
            }
            else {
                $(this).next('.sub-menu').animate({
                    'max-height': '1000px'
                }, 300);
            }
            $(this).toggleClass('active');
        });

        var $el = $(this).find('.current-page').parents('.sub-menu');
        $el.each(function () {
            $(this).prev('a').click();
        });

    });
    return this;
};
app.InitSlideRange = function () {
    $('.js-filter-range').each(function () {
        var max = $(this).attr('data-max'),
            min = $(this).attr('data-min'),
            val = $(this).attr('data-value').split(','),
            $this = $(this);

        $(this).slider({
            range: true,
            min: min * 1,
            max: max * 1,
            //step: 100,
            values: [val[0] * 1, val[1] * 1],
            slide: function (event, ui) {
                var $h = $this.find('.ui-slider-handle'),
                    $i = $this.find('input[type=hidden]');
                $($h[0]).attr({
                    'data-val': (ui.values[0] + '').replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ")
                });
                $($h[1]).attr({
                    'data-val': (ui.values[1] + '').replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ")
                });

                $($i[0]).val(ui.values[0]);
                $($i[1]).val(ui.values[1]);

                //console.log($h);
            }
        });

        var $h = $this.find('.ui-slider-handle'),
            $i = $this.find('input[type=hidden]');

        $($h[0]).attr({
            'data-val': (val[0] + '').replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ")
        });
        $($h[1]).attr({
            'data-val': (val[1] + '').replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ")
        });
        $($i[0]).val(val[0]);
        $($i[1]).val(val[1]);
    });
    return this;
};
app.InitHideBigCnt = function () {
    $('.main-container .hide-big-cnt').each(function () {
        if (!$(this).find('.hide-block').length) {
        }
        else {
            $(this).find('.hide-block').hide();
            var $triger = $('<a class="hide-big-target" href="#">' + $(this).attr('data-hide') + '</a>');
            $('.hide-block').after($triger);
            var $this = $(this);
            $triger.click(function (e) {
                e.preventDefault();
                $this.find('.hide-block').slideDown(400, function () {
                    $(window).resize();
                });
                $triger.hide();
            });
        }

    });
    return this;
};
app.VerticalCenter = function () {
    $('.js-vertical-center').each(function () {
        var t = $(this);
        t.css({
            'margin-top': (t.parent().outerHeight() - t.outerHeight()) / 2
        });
    });
    return this;
};
app.initNormalSlider = function () {
    setTimeout(function () {
        $('.js-normal-slider-init').each(function () {
            var next = $(this).attr('data-next');
            var prev = $(this).attr('data-prev');
            //var width = $(this).width();
            var width = $(this).parent().width();
            var parentW = $(this).attr('data-width');
            $(this).bxSlider({
                pager: false,
                nextText: '',
                prevText: '',
                nextSelector: $(next),
                prevSelector: $(prev),
                infiniteLoop: false,
                minSlides: 1,
                maxSlides: 1,
                slideMargin: 0,
                hideControlOnEnd: true,
                slideWidth: width
            });

            $(next).find('a').click(function (e) {
                e.preventDefault();
            });
            $(prev).find('a').click(function (e) {
                e.preventDefault();
            });
        });
    }, 100 / 10);
    return this;
}
app.initContactMap = function () {
    if ($('.contact-page').length) {
        var lat = $('#map').attr('data-lat');
        var long = $('#map').attr('data-long');
        var myLatLng = new google.maps.LatLng(lat, long);
        var mapOptions = {
            scrollwheel: false,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            zoom: 16,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);

        var image = '/images/pin_map.png';

        var beachMarker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: image
        });
    }

    if ($('.contact-media-page').length) {
        var myLatLng = new google.maps.LatLng(55.5506236, 37.5465014);
        var mapOptions = {
            scrollwheel: false,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.LARGE,
                position: google.maps.ControlPosition.RIGHT_CENTER
            },
            zoom: 16,
            center: myLatLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);

        var image = '/images/pin_map2.png';

        var beachMarker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            icon: image
        });
    }

    return this;
};
app.docCheckbox = function () {

    $('.js-doc-check').each(function () {
        var $cnt = $(this);
        $cnt.find('.head_sub').find('input').change(function () {
            var $par = $(this).closest('.head_sub'),
                $next = $par.nextAll('.head_sub, .head').filter(':first');
            var s = $par.length ? $par.index() + 1 : 0,
                e = $next.length ? $next.index() : $cnt.find('li').length;
            if ($(this).filter(":checked").length) {
                for (var i = s; i < e; i++) {
                    $cnt.find('li').eq(i).find('input').prop({
                        "checked": true
                    });
                    $cnt.find('li').eq(i).slideDown();
                }
            }
            else {
                for (var i = s; i < e; i++) {
                    $cnt.find('li').eq(i).find('input').prop({
                        "checked": false
                    });
                    $cnt.find('li').eq(i).slideUp();
                }
            }

            $par = $(this).closest('.head_sub');
            var $H = $par.prevAll('.head').filter(':first'),
                $P = $par.nextAll('.head').filter(':first');
            s = $H.length ? $H.index() + 1 : 0, e = $P.length ? $P.index() : $cnt.find('li').length;
            var isCheck = true;
            for (var i = s; i < e; i++) {
                if (!$cnt.find('li').eq(i).find('input').filter(":checked").length) {
                    isCheck = false;
                }
            }
            if (isCheck) {
                $H.find('input').prop({
                    "checked": true
                });
            }
            else {
                $H.find('input').prop({
                    "checked": false
                });
            }

        });
        $cnt.find('.head').find('input').change(function () {
            var $par = $(this).closest('.head'),
                $next = $par.nextAll('.head').filter(':first');
            var s = $par.length ? $par.index() : 0,
                e = $next.length ? $next.index() : $cnt.find('li').length;
            if ($(this).filter(":checked").length) {
                for (var i = s; i < e; i++) {
                    $cnt.find('li').eq(i).find('input').prop({
                        "checked": true
                    });
                }
            }
            else {
                for (var i = s; i < e; i++) {
                    $cnt.find('li').eq(i).find('input').prop({
                        "checked": false
                    });
                }
            }

        });
        $cnt.find('.item').find('input').change(function () {
            var $par = $(this).closest('.item'),
                $H = $par.prevAll('.head_sub').filter(':first'),
                $P = $par.nextAll('.head_sub').filter(':first');
            var s = $H.length ? $H.index() + 1 : 0,
                e = $P.length ? $P.index() : $cnt.find('li').length;
            var isCheck = true;
            for (var i = s; i < e; i++) {
                if (!$cnt.find('li').eq(i).find('input').filter(":checked").length) {
                    isCheck = false;
                }
            }
            if (isCheck) {
                $H.find('input').prop({
                    "checked": true
                });
            }
            else {
                $H.find('input').prop({
                    "checked": false
                });
            }

            $par = $(this).closest('.item');
            $H = $par.prevAll('.head').filter(':first');
            $P = $par.nextAll('.head').filter(':first');
            s = $H.length ? $H.index() + 1 : 0, e = $P.length ? $P.index() : $cnt.find('li').length;
            isCheck = true;
            for (var i = s; i < e; i++) {
                if (!$cnt.find('li').eq(i).find('input').filter(":checked").length) {
                    isCheck = false;
                }
            }
            if (isCheck) {
                $H.find('input').prop({
                    "checked": true
                });
            }
            else {
                $H.find('input').prop({
                    "checked": false
                });
            }
        });

    });
    return this;
};
app.PerechenTab = function () {
    var trigger = $('.js-trigger-perechen');
    trigger.each(function () {
//        if ($(this).hasClass('active')) {
//            $('.js-target-perechen').addClass($(this).attr('href').replace('#', ''));
//        }
        $(this).click(function (e) {
            e.preventDefault();
            trigger.removeClass('active');
            $(this).addClass('active')
            $('.js-target-perechen').removeClass('list');
            $('.js-target-perechen').removeClass('grid');
            $('.js-target-perechen').addClass($(this).attr('href').replace('#', ''));
            document.cookie = "catalog-view-type=" + $(this).attr('href').replace('#', '') + ";";
        })
    });
    return this;
};
app.ProductSlider = function () {
    var sliders = [];
    setTimeout(function () {
        $('.bxslider').each(function () {
            var $this = $(this);
            sliders[sliders.length] = $this.find('.bx-wrap').bxSlider({
                pagerCustom: $this.find('.tumb-img'),
                nextSelector: $this.find('.controls .next'),
                prevSelector: $this.find('.controls .prev'),
                nextText: '',
                prevText: '',
                responsive: true,
                hideControlOnEnd: true,
                infiniteLoop: false
            });
        });
    }, 1000 / 10);


    $('.tabs-similar').each(function () {
        var $triger = $('li a', this),
            $tabs = $('.items-similar');
        $triger.click(function (e) {
            e.preventDefault();
            if (!$tabs.hasClass('active')) {
                $('.item-similar.active', $tabs).removeClass('active').hide();
                $($(this).attr('href')).addClass('active').show();
                $triger.removeClass('active');
                $(this).addClass('active');
            }
        });
    });

    setTimeout(function () {
        $('.js-slider-similar').each(function () {
            var $cont = $($(this).attr('data-control'));
            var width = $(this).width();
            //console.log(width);
            $(this).prop('slider', $(this).bxSlider({
                minSlides: 3,
                maxSlides: 3,
                slideWidth: width / 3,
                slideMargin: 10,
                pager: false,
                adaptiveHeight: true,
                nextSelector: $cont.find('.next'),
                prevSelector: $cont.find('.prev'),
                nextText: '',
                prevText: '',
                hideControlOnEnd: true,
                infiniteLoop: false
            }));
        });
        $('.items-similar .item-similar').hide();
        $('.items-similar .item-similar.active').show();
    }, 400 / 10);


    return this;
}
/*$('.close-popup, #overlay').click(function (e) {
 console.log(e.target.getAttribute('id'));
 if (e.target.getAttribute('id') == 'overlay-box' || e.target.getAttribute('class') == 'close') {
 $('.popup-box').fadeOut(300, function () {
 $('#overlay').fadeOut(500);
 });
 } else {
 return false;
 }
 });*/
app.popup = function () {
    $('.popup-cnt').each(function () {
        var $cnt = $(this).find('.inner-cnt');
        if ($cnt.outerHeight() > $(window).height()) {
            $cnt.css({"transform": "translate(-50%, 50%) scale(" + (Math.round(($(window).height() - 50) / $cnt.outerHeight() * 100) / 100) + ")"});
        }

        $(this).css('opacity', 0).hide();
        $cnt.find('.close').click(function (e) {
            e.preventDefault();
            $('body').removeClass('no-scroll');
            $cnt.parent().animate({
                'opacity': 0
            }, 300, function () {
                $cnt.parent().hide();
                if ($cnt.attr('id') == 'comm-ok') {
                    $('#comment-form button').click();
                }
            })
        });
        $(this).click(function (e) {
            if (e.target.getAttribute('class') == 'popup-cnt') {
                e.preventDefault();
                $('body').removeClass('no-scroll');
                $cnt.parent().animate({
                    'opacity': 0
                }, 300, function () {
                    $cnt.parent().hide();
                    if ($cnt.attr('id') == 'comm-ok') {
                        $('#comment-form button').click();
                    }
                });
            }
        });
        $(document).on('keyup', function (e) {
            if (e.keyCode == 27) {
                $('body').removeClass('no-scroll');
                $cnt.parent().animate({
                    'opacity': 0
                }, 300, function () {
                    $cnt.parent().hide();
                    if ($cnt.attr('id') == 'comm-ok') {
                        $('#comment-form button').click();
                    }
                });
                return false;
            }
        });
    });
    $('.js-popup-open').click(function (e) {
        e.preventDefault();
        $('body').addClass('no-scroll');
        var id = $(this).attr('data-popup');
        var $cnt = $(id);
        $cnt.find('.inner').show();
        $cnt.find('.success').hide();

        $(id).parent().show().animate({
            'opacity': 1
        }, 300);
        if ($cnt.outerHeight() > $(window).height()) {
            $cnt.css({"transform": "translate(-50%, -50%) scale(" + (Math.round(($(window).height() - 50) / $cnt.outerHeight() * 100) / 100) + ")"});
        }
    });
    return this;
};

app.validationForm = function () {
    var self = this;
    this.messages = {
        en: {
            'Заполните, пожалуйста, это поле': 'Please fill in this field',
            'Заполните это поле правильно': 'Fill in this field correctly',
            'Введите настоящий E-mail': 'Enter valid E-mail'
        },
        ru: {
            'Заполните, пожалуйста, это поле': 'Заполните, пожалуйста, это поле',
            'Заполните это поле правильно': 'Заполните это поле правильно',
            'Введите настоящий E-mail': 'Введите настоящий E-mail'
        }
    };
    self.messages = this.messages[$('html').data('lang')];

    var isEmail = function (email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        },
        isEmpty = function (string) {
            return string.length > 0;
        },
        isPhone = function (phone) {
            return phone.length >= 17;
        };

    $('.js-validation-form').submit(function (e) {
        $(this).find('.js-validation-empty select').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({
                    'data-error': self.messages['Заполните, пожалуйста, это поле']
                });
            }
            else {
                $(this).parent().removeClass('error')
            }
        });
        $(this).find('.js-validation-empty .inputtext').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({
                    'data-error': self.messages['Заполните, пожалуйста, это поле']
                });
            }
            else {
                $(this).parent().removeClass('error')
            }
        });

        $(this).find('.js-validation-empty .inputfile').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({
                    'data-error': self.messages['Заполните, пожалуйста, это поле']
                });
            }
            else {
                $(this).parent().removeClass('error')
            }
        });

        $(this).find('.js-validation-empty .inputtextarea').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({
                    'data-error': self.messages['Заполните, пожалуйста, это поле']
                });
            }
            else {
                $(this).parent().removeClass('error')
            }
        });

        $(this).find('.js-validation-phone .inputtext').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({
                    'data-error': self.messages['Заполните, пожалуйста, это поле']
                });
            }
            else {
                if (!isPhone($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({
                        'data-error': self.messages['Заполните это поле правильно']
                    });
                }
                else {
                    $(this).parent().removeClass('error')
                }
            }
        });

        $(this).find('.js-validation-email .inputtext').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({
                    'data-error': self.messages['Заполните, пожалуйста, это поле']
                });
            }
            else {
                if (!isEmail($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({
                        'data-error': self.messages['Введите настоящий E-mail']
                    });
                }
                else {
                    $(this).parent().removeClass('error')
                }
            }
        });

    });
    return this;
};
app.learnCalendar = function () {
    $('#learn-calendar').each(function () {
        var dates = {};
        var id = $(this).attr('data-datepicker');
        $(id).find('li').each(function () {
            if (dates[new Date($(this).attr('data-date')).getTime()] == undefined) {
                dates[new Date($(this).attr('data-date')).getTime()] = $(this).html();
            }
            else {
                dates[new Date($(this).attr('data-date')).getTime()] += "<hr>" + $(this).html();
            }

        });
        $(this).datepicker({
            showOtherMonths: true,
            beforeShowDay: function (date) {
                var hlText = dates[date.getTime()];
                var seminarDate = date.getTime();
                var now = new Date().getTime();
                //console.log(date.getTime(), now.getTime());
                return (hlText) ? [true, (seminarDate < now) ? "js-tooltipL-hover past" : "js-tooltipL-hover", hlText] : [true, '', ''];
            },
            onSelect: function (dateText, inst) {
                setTimeout(function () {
                    app.initCalendarLearnTooltip();
                    $('td', this).unbind('click');
                }, 5);
                return false;
            }
        });
        if ($(this).data("lang")) {
            $(this).datepicker("option", $.datepicker.regional[ $(this).data("lang") ] );
        }

        $(this).click(function () {
            app.initCalendarLearnTooltip();
            $('td', this).unbind('click');
        });
        $('td', this).unbind('click');
    });
    return this;
}
app.initCalendarLearnTooltip = function () {
    $('.js-tooltipL-hover').each(function () {
        var a = $('.ui-state-default', this);
        a.detach();
        $(this).find('.content').detach();
        var width = $(this).width();
        var html = $(this).attr('title');
        if (!html.length) {
            html = $(this).attr('data-title');
        }
        $(this).attr('title', '');
        $(this).attr('data-title', html);
        if (html.length) {
            $(this).append('<div class="content"><div class="inner">' + html + '</div></div>');
        }

        a.prependTo($(this).find('.content'));
        //$(this).find('.content').append(a);
    });
    $('.ui-state-default').unbind('click').click(function (e) {
        e.preventDefault();
    });
    return this;
};
app.sidebarPopup = function () {
    $('.sidebar-popup').css({
        'right': -400
    });
    $('.js-open-sidebar-popup').click(function (e) {
        $('.sidebar-popup').css({
            'right': 0
        });
        e.preventDefault();
    });
    $('.js-close-sidebar-popup').click(function (e) {
        $('.sidebar-popup').css({
            'right': -400
        });
        e.preventDefault();
    });
    return this;
};
$.datepicker.regional['ru'] = {
    closeText: 'Закрыть',
    prevText: '&#x3c;Пред',
    nextText: 'След&#x3e;',
    currentText: 'Сегодня',
    monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
        'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
    ],
    monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
        'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'
    ],
    dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
    dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
    dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    dateFormat: 'dd.mm.yy',
    firstDay: 1,
    isRTL: false
};
$.datepicker.setDefaults($.datepicker.regional['ru']);
$(function () {
    /*if ($('.list-href').length){
     console.log('+');
     setTimeout(function () {
     console.log('++');
     $('.list-href .item').eq(0).children('a').click();
     },500);
     }*/
    $('body').append('<div class="js-tooltip tooltip-cnt">' +
        '<div class="time"></div>' +
        '<div class="text"></div>' +
        '<div class="close"></div>' +
        '</div>');

    if ($('.index-page').length) {
        $('.index-page .wrapper').addClass('js-fh');
    }

    var fixSearch = function (count) {
        $('.search_page').find('.perechen-produts').each(function () {
            var el = $(this).find('>li:not(.clearfix-col3):not(.clearfix-col3)');
            for (var i = count - 1; i <= el.length; i = i + count) {
                $(el[i]).after("<li class='clearfix-col" + count + "'></li>");
            }
        });
    };
    fixSearch(3);
    fixSearch(2);

    app.init().
    initFullHeight().
    initFullHeightParent().
    initFullDocument().
    initMenu().
    initFixedBlock().
    initDatepicker().
    initSideNews().
    initCalendarTooltip().
    initIsSidebar().
    initCompanySlider().
    accordion().
    initPopUpImg().
    equalHeight().
    initTabs().
    menuSelect().
    InitSlideRange().
    InitHideBigCnt().
    VerticalCenter().
    initNormalSlider().
    initContactMap().
    docCheckbox().
    PerechenTab().
    ProductSlider().
    popup().
    validationForm().
    learnCalendar().
    initCalendarLearnTooltip().
    sidebarPopup();

    app.converter_properties($('.blur-main .main-sidebar'), $('.header-cnt'), 'width', 'padding-left');
    app.converter_properties($('.blur-main .main-sidebar'), $('.blur-main'), 'width', 'width');
    $('body').removeClass('no-load');
});
$(document).ready(function () {
    app.initViewIcoNextBack();
    if ($(".phone input").length > 0) {
        $(".phone input").mask("+7 (999) 999-9999", {
            placeholder: "+7 (___) ___-____"
        });
    }
    $('.zones h2.label').click(function () {

    });
    //todo andrew
    $(window).resize(function () {
        $(".main-container").css({
            paddingLeft: "0px"
        });
        $(".index-page .main-container").css({
            height: $(window).outerHeight() - 40,
            paddingLeft: "0px",
            overflow: "hidden"
        });
        $(".search-page .main-container").css({
            height: $(window).outerHeight() - 40,
            paddingLeft: "0px",
            overflow: "hidden"
        });
        app.initFullHeight();
    });
    $(document).click(function () {
        $(".main-container").css({
            paddingLeft: "0px"
        });
        $(".index-page .main-container").css({
            height: $(window).outerHeight() - 40,
            paddingLeft: "0px",
            overflow: "hidden"
        });
        $(".search-page .main-container").css({
            height: $(window).outerHeight() - 40,
            paddingLeft: "0px",
            overflow: "hidden"
        });
        app.initFullHeight();
        setTimeout(function () {
            $(".main-container").css({
                paddingLeft: "0px"
            });
            $(".index-page .main-container").css({
                height: $(window).outerHeight() - 40,
                paddingLeft: "0px",
                overflow: "hidden"
            });
            $(".search-page .main-container").css({
                height: $(window).outerHeight() - 40,
                paddingLeft: "0px",
                overflow: "hidden"
            });
            app.initFullHeight();
        }, 100 / 10);
    });
    $(".main-container").css({
        paddingLeft: "0px"
    });
    $(".index-page .main-container").css({
        height: $(window).outerHeight() - 40,
        paddingLeft: "0px",
        overflow: "hidden"
    });
    $(".search-page .main-container").css({
        height: $(window).outerHeight() - 40,
        paddingLeft: "0px",
        overflow: "hidden"
    });

    if ($('.js-company-slider').length) {
        setTimeout(function () {
            $('.js-company-slider').each(function () {
                var width = $(this).width() / 6;
                $(this).bxSlider({
                    minSlides: 6,
                    maxSlides: 6,
                    slideMargin: 22,
                    slideWidth: width - 22,
                    pager: false,
                    nextText: '',
                    prevText: '',
                    nextSelector: $('#js-control-company').find('.next'),
                    prevSelector: $('#js-control-company').find('.prev'),
                    hideControlOnEnd: true,
                    infiniteLoop: false
                });
            });
        }, 60 / 10);
    }
    if ($('.js-solutions-slider').length) {
        //setTimeout(function () {
        $('.js-solutions-slider').each(function () {
            var width = $(this).width() / 3;
            $(this).bxSlider({
                minSlides: 3,
                maxSlides: 3,
                slideMargin: 22,
                slideWidth: width - 22,
                pager: false,
                nextText: '',
                prevText: '',
                nextSelector: $('#js-solutions-company').find('.next'),
                prevSelector: $('#js-solutions-company').find('.prev'),
                hideControlOnEnd: true,
                infiniteLoop: false
            });
        });
        //}, 60);
    }

    if ($('.js-normal-slider').length) {
        $('.js-normal-slider').each(function () {
            var next = $(this).attr('data-next');
            var prev = $(this).attr('data-prev');
            $(this).bxSlider({
                pager: false,
                nextText: '',
                prevText: '',
                nextSelector: $(next),
                prevSelector: $(prev),
                infiniteLoop: false,
                hideControlOnEnd: true,
                minSlides: 1,
                maxSlides: 1
            });

            $(next).find('a').click(function (e) {
                e.preventDefault();
            });
            $(prev).find('a').click(function (e) {
                e.preventDefault();
            });
        });
    }

    if ($('.js-normal-slider3').length) {
        $('.js-normal-slider3').each(function () {
            var next = $(this).attr('data-next');
            var prev = $(this).attr('data-prev');
            //
            $(this).css('display', 'block').css('overflow', 'hidden');
            var width = $(this).width() / 2;
            //
            $(this).bxSlider({
                pager: false,
                nextText: '',
                prevText: '',
                nextSelector: $(next),
                prevSelector: $(prev),
                infiniteLoop: false,
                hideControlOnEnd: true,
                minSlides: 2,
                maxSlides: 2,
                //
                slideMargin: 0,
                slideWidth: width
                //
            });

            $(next).find('a').click(function (e) {
                e.preventDefault();
            });
            $(prev).find('a').click(function (e) {
                e.preventDefault();
            });
        });

    }

    var aForm = $('a.sopr');
    if (aForm.length) {
        aForm.siblings('textarea').hide();
        aForm.click(function () {
            $(this).siblings('textarea').slideToggle();
            return false;
        });
    }

    $('input.inputfile').change(function () {
        var inputFile = $(this).val(),
            file_name = inputFile.replace("C:\\fakepath\\", '');
        $('a.resume').parent().append('<span> : ' + file_name + '</span>');
    });

    $('.field.custom_upload.white-btn input[type=file]').change(function () {
        var inputFile = $(this).val(),
            file_name = inputFile.replace("C:\\fakepath\\", '');
        $(this).parent().attr('data-new-file', file_name);
    });

    $('.js-accordion-new').click(function () {
        $(this).parent().addClass('active');
        $(this).parent().children('#newsFilter').slideToggle();
        $(this).parent().children('#newsFilter').find('select').each(function () {
            $(this).find('option[selected]').removeAttr("selected");
        });
    });

    if ($('.form-desc-txt').length) {
        $('.form-desc-txt .show-js-validation-form-new').click(function () {
            $(this).parent().slideUp(400);
            $('.success-message').hide();
            //$('.js-validation-form-new').wrap('<div class="preview-project-viewport"><div
            // class="preview-project-viewport-inner"></div></div>');
            $('.js-validation-form-new').slideDown(400, function () {
                $(window).resize();
            });

        });
    }

    var form = $('#popup-subscribe-email form');
    form.submit(function () {
        var bError = false;
        var subsEmail = form.find('input[type=text]');
        if (subsEmail.val() != '' || subsEmail.val() != undefined) {
            var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            if (!pattern.test(subsEmail.val())) {
                subsEmail.parent().addClass('error');
                bError = true;
            }
        }

        if (!bError) {
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                success: function (data) {
                    if (data == 1) {
                        $('#popup-subscribe-email .inner.form-cont-box, #popup-subscribe hr').slideUp(400, function () {
                            $('#popup-subscribe-email .inner.success').slideDown(400);
                        });
                    }
                    else {
                        $('#popup-subscribe-email .field.custom_label').addClass('error');
                    }
                }
            });
            return false;
        }
        return false;
    });

    var formPhone = $('#popup-subscribe-phone form');
    formPhone.submit(function () {
        var bError = false;
        var subsPhone = formPhone.find('input[name=sending_phone]');
        var userSign = formPhone.find('input[name=user_msg]');
        if (subsPhone.val() == '' || subsPhone.val().length < 16) {
            subsPhone.parent().addClass('error');
        }
        if (userSign.val() != '' && userSign.val().length > 25) {
            userSign.parent().addClass('error');
        }
        else {
            $.ajax({
                type: formPhone.attr('method'),
                url: formPhone.attr('action'),
                data: formPhone.serialize(),
                success: function (data) {
                    if (data == 1) {
                        $('#popup-subscribe-phone .inner.form-cont-box, #popup-subscribe-phone hr').slideUp(400, function () {
                            $('#popup-subscribe-phone .inner.success').slideDown(400);
                        });
                    }
                    else {
                        $('#popup-subscribe .field.custom_label').addClass('error');
                    }
                }
            });

        }
        return false;
    });

    $('.b-box').click(function () {

        if ($('#add-new-comment textarea').val() != '') {
            $('#add-new-comment textarea').removeClass('error');
            $('.b.js-popup-open').click();

        }
        else {
            $('#add-new-comment textarea').addClass('error');
        }
    });

    $('.zones > h2').click(function () {
        var zones = $(this).parent().find('.zones-wrap');
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            $(this).parent().css('height', $(this).parent().attr('data-zone-h') + 'px');
        }
        else {
            $(this).parent().css('height', $(this).parent().attr('data-h2h') + 'px');
        }
    });

    $('.search-cnt input').on('keypress', function (event) {
        if (event.which == '13') {
            if ($(this).val().length < 3) {
                $(this).parent().parent().addClass('error');
                event.preventDefault();
            }
            else {
                $(this).parent().parent().removeClass('error');
            }
        }
    });

    $(".search-cnt form").bind("submit", function () {
        if ($(this).find("input").val().length < 3) {
            $(this).parent().parent().addClass('error');
            return false;
        }
        return true;
    });
    $('.js-slider-similar .param .open').click(function () {
        //console.log($(this).parents());
        var $this = $(this).parents('.js-slider-similar'),
            $thisViewport = $(this).parents('.bx-viewport'),
            $thisLI = $(this).parent().parent(),
            liOpen = $(this);

        if (!liOpen.hasClass('opened')) {
            $(this).parent().find('.hide').each(function (i) {
                //if(i<=3){
                $(this).parent().find('.hide, .was_hidden').eq(i).removeClass('hide').addClass('was_hidden');

                //} else {
                //    return false;
                //}
            });
            liOpen.addClass('opened').text("Свернуть");
        }
        else {
            $(this).parent().find('.was_hidden').each(function (i) {
                //if(i<=3){
                $(this).parent().find('.was_hidden').eq(i).addClass('hide');
                //} else {
                //    return false;
                //}
            });
            liOpen.removeClass('opened').text("Развернуть");
        }
        // $(this).slideUp(400, function() {
        //console.log($thisLI, ' | ', $thisLI.outerHeight());
        $this.css({
            'min-height': $thisLI.outerHeight() + 100
        });
        $thisViewport.css({
            'min-height': $thisLI.outerHeight() + 100
        });
        //});
    });
    if ($('.col3.param-cnt').length) {
        /*$('.col3.param-cnt .param > li').each(function () {
         if ($(this).index() > 2 && !$(this).hasClass('open')) {
         $(this).css('display', 'none');
         }
         });*/
        $('.col3.param-cnt .param > li.open').click(function () {
            var liOpen = $(this),
                container = $(this).parent(),
                hiddenNotes = container.find('.note:hidden').addClass('was_hidden');

            if (!liOpen.hasClass('opened')) {
                container.find('.was_hidden').slideDown(400, function () {
                    //liOpen.slideUp();
                    liOpen.addClass('opened').text("Свернуть");

                });
            }
            else {
                container.find('.was_hidden').slideUp(400, function () {
                    //liOpen.slideUp();
                    liOpen.removeClass('opened').text("Развернуть");

                });
            }

        });

    }
    $('.head-links li').each(function () {
        $(this).attr('w', $(this).outerWidth() + 4);
    });
    var linksSize = $('.head-links li').length;
    if ($('.head-links').length) {
        var liW = [],
            flag = false;
        $('.head-links li').each(function () {
            var linW = $(this).attr('w');
            liW.push(linW);
        });
        $(window).resize(function () {
            var ulParamWidth = $('.head-links').outerWidth(),
                linksWidthSum = 0,
                linksToString = 0;

            for (var i = 0; i < liW.length; i++) {
                linksWidthSum += parseInt(liW[i]);
                if (linksWidthSum > ulParamWidth) {
                    linksToString = i;
                    flag = true;
                    //console.log(i, linksToString, linksWidthSum, 'linksWidthSum=', linksWidthSum, ' ulParamWidth=',
                    // ulParamWidth);
                    /* if (linksWidthSum > ulParamWidth ) {
                     $('.head-links-button').slideDown();
                     } else {
                     $('.head-links-button').slideUp();
                     }*/
                }
                else {
                    flag = false;
                }
            }
            if (flag == true) {
                $('.head-links-button').slideDown();

            }
            else {
                $('.head-links-button').slideUp();
            }
            //console.log('linksWidthSum=', linksWidthSum, ' ulParamWidth=', ulParamWidth, ' | ',
            // parseInt(linksWidthSum / ulParamWidth));
        });
    }
    $('.head-links-button').click(function () {
        $(this).toggleClass('active');
        $(this).parent().find('.head-links-wrapper').toggleClass('open');
        setTimeout(function () {
            $(window).resize();
        }, 500 / 10);
        if ($(this).hasClass('active')) {
            $(this).html('свернуть');
        }
        else {
            $(this).html('развенуть');
        }
    });
    $('.proj-video-box .open-video').click(function () {
        var $pop = $('#proj-video'),
            videoFile = $('.proj-video-player');
        $pop.fadeIn(400, function () {
            $(window).resize();
        });
    });
    $('#proj-video .close').click(function () {

        var player = $(this).parent().parent().find('.proj-video-player');
        var playerID = player.children('div').eq(0).attr('id');
        if (playerID) {

            playerID = playerID.substr(0, playerID.length - 4);
        }
        $(this).parent().parent().fadeOut(400, function () {
            //$(this).find('video').stop();
            if (playerID) {
                jwplayer().stop();
            }
        });

    });


    var slider_knobs = [
        '.noUi-handle.noUi-handle-lower',
        '.noUi-handle.noUi-handle-upper'
    ];

    var slide_inputs = [
        'input.slider-min',
        'input.slider-max'
    ];

    $('.value-range-slider').each(function (i, e) {
        var slider = $(e).get(0),
            start_min_value = $(e).data('start-min-value'),
            start_max_value = $(e).data('start-max-value'),
            min_value = $(e).data('min-value'),
            max_value = $(e).data('max-value');

        noUiSlider.create(slider, {
            start: [start_min_value, start_max_value],
            connect: true,
            range: {
                'min': min_value,
                'max': max_value
            }
        });

        slider.noUiSlider.on('change', function (values, handle, unencodedValues) {
            var current_values = $(e).get(0).noUiSlider.get();
            //console.log(current_values);
            $.each(current_values, function (i, val) {
                $(e).siblings(slide_inputs[i]).val(val);
            })
        });
        slider.noUiSlider.on('update', function (values, handle, unencodedValues) {
            if ($(e).data('format') == 'money') {
                unencodedValues[handle] = parseFloat(unencodedValues[handle]).formatMoney(0, '.', ' ');
            }
            else if ($(e).data('format') == 'float') {
                unencodedValues[handle] = parseFloat(unencodedValues[handle]).formatMoney(2, '.', ' ');
            }
            $(e).find(slider_knobs[handle]).attr('data-val', unencodedValues[handle]);
        });

    });

});

$(window).load(function () {
    $(this).resize();
    //console.log(navigator.userAgent);
    if (navigator.userAgent.indexOf('Mac') > 0) {
        $('body').addClass('mac-os');
        //var elemHTML = document.getElementsByTagName('html')[0];
        //
        //elemHTML.className += "inner no-full";
        //
        //if (navigator.userAgent.indexOf('Safari') > 0) elemHTML.className += " mac-safari";
        //if (navigator.userAgent.indexOf('Chrome') > 0) elemHTML.className += " mac-chrome";
    }
});


function ajaxForm(ajaxForm, replace_html, callback, reload) {
    var form_id = $(ajaxForm).data("id");
    var method = typeof $(ajaxForm).attr('method') !== 'undefined' ? $(ajaxForm).attr('method') : 'post';
    var dataType = typeof $(ajaxForm).attr('data-return-type') !== 'undefined' ? $(ajaxForm).attr('data-return-type') : 'html';
    console.log($(ajaxForm).attr('action'));
    $.ajax({
        data: $(ajaxForm).serialize(),
        url: $(ajaxForm).attr('action'),
        type: method,
        dataType: dataType,
        success: function (res) {
            console.log(res);
            var $ajaxResponse = $(res);
            if (typeof callback === 'function') {
                callback.call(false, res);
            }
            else {
                if (replace_html && $ajaxResponse.find('form').length) {
                    console.log($ajaxResponse.html());
                    $(ajaxForm).html($ajaxResponse.html());
                }
                else {
                    $ajaxResponse.hide().insertAfter($(ajaxForm));
                    $(ajaxForm).fadeOut("600", function () {
                        $ajaxResponse.fadeIn(600, function () {
                            if (reload || $(ajaxForm).is('.reload')) {
                                location.reload();
                            }
                        });
                    });
                }
            }
            var callbacks = $(ajaxForm).data('callbacks');
            /*if ( typeof callbacks != 'undefied') {
             init_forms(callbacks);
             } else {
             init_forms();
             }*/
        },
        error: function (xhr, stat, mess) {
            console.log(mess);
        }
    });
}


function initSubmitRegular(submit_form) {
    if ($(submit_form).is('.validate')) {
        $(submit_form).validate({
            submitHandler: function (form) {
                ajaxForm(form, false, function (result) {
                    if ($(result).find('[name=success]').val() || $(result).filter('[name=success]').val()) {
                        console.log(result);
                        if ($(submit_form).is('.reload')) {
                            location.reload();
                        }
                        else if ($(submit_form).is('.reload-timeout')) {
                            setTimeout(function () {
                                location.reload()
                            }, 500);
                        }
                        else if (($(submit_form).is('.result-redirect') || $(submit_form).is('.result-redirect-timeout')) && $(submit_form).data('success-url')) {
                            var result_id = $(result).find('[name=result_id]').val();
                            if (!result_id) {
                                result_id = $(result).filter('[name=result_id]').val();
                            }

                            var result_url = "/learn/result.php?find_id=" + result_id;
                            if ($(submit_form).is('.result-redirect-timeout')) {
                                setTimeout(function () {
                                    window.location.href = result_url
                                    //window.location.href = result_url
                                }, 500);
                            }
                            else {
                                window.location.href = result_url;
                            }

                        }
                    }
                    $(submit_form).html(result);
                    initUserForms();
                });
            },
            invalidHandler: function (event, validator) {
                console.log(event, validator);
            }
            //                        errorPlacement: function (error, element) {
            //                            console.log(error, element);
            //                        }
        });
    }
    else {
        $(submit_form).on('submit', function (e) {
            e.preventDefault();
            ajaxForm(this, false, function (result) {
                if ($(result).find("[name=success]").val()) {
                    location.reload();
                }
                else {
                    // Fix for /learn/result.php
                    if ($(submit_form).hasClass("submit-result-field")) {
                        $(submit_form).find(".submit-result-msg").html(result);
                        $(submit_form).find(".submit-result").removeClass("hide", 500);
                    } else {
                        $(submit_form).html(result);
                    }
                    initUserForms();
                }
            });
        });
    }
}

function initSubmitMerged(submit_form) {
    document.merged_forms_cache.push({
        'form': submit_form,
        'name': $(submit_form).attr('name'),
        'success': 'N',
        //'proccess':'N',
        'html': $(submit_form).get(0).outerHTML,
        'valid': 'Y'
    });


    if ($(submit_form).is('.validate')) {
        $(submit_form).validate({
            submitHandler: function (form) {
                if (checkMergeFormsValid(form)) {
                    ajaxForm(form, false, function (result) {
                        if (checkMergeFormsSuccess(form, result)) {
                            if ($(submit_form).is('.reload')) {
                                location.reload();
                            }
                            else if ($(submit_form).is('.reload-timeout')) {
                                setTimeout(function () {
                                    location.reload()
                                }, 500);
                            }
                            else if ($(submit_form).is('.result-redirect') || $(submit_form).is('.result-redirect-timeout')) {
                                mergeFormsResultRedirect(submit_form);
                            }
                            setSuccessMergeForms(form, result);
                        }
                        else {
                            if ($(result).find('[name=success]').val() != "Y" && !$(result).filter('[name=success]').val() != "Y") {
                                $(submit_form).html(result);
                            }
                        }
                        /*if ($(result).find('[name=success]').val() || $(result).filter('[name=success]').val()) {
                         if ($(submit_form).is('.reload')) {
                         location.reload();
                         } else if ($(submit_form).is('.reload-timeout')) {
                         setTimeout(function () {location.reload()}, 500);
                         }
                         }*/
                        //$(submit_form).html(result);
                        initUserForms();
                    });
                }
            }
//                        errorPlacement: function (error, element) {
//                            console.log(error, element);
//                        }
        });
    }
    else {
        $(submit_form).on('submit', function (e) {
            e.preventDefault();
            ajaxForm(this, false, function (result) {
                if (checkMergeFormsSuccess(form, result)) {
                    if ($(submit_form).is('.reload')) {
                        location.reload();
                    }
                    else if ($(submit_form).is('.reload-timeout')) {
                        setTimeout(function () {
                            location.reload()
                        }, 500);
                    }
                    else if ($(submit_form).is('.result-redirect')) {
                        mergeFormsResultRedirect(submit_form);
                    }
                    setSuccessMergeForms(form, result);
                }
                else {
                    if ($(result).find('[name=success]').val() != "Y" && !$(result).filter('[name=success]').val() != "Y") {
                        $(submit_form).html(result);
                    }
                }
                /*console.log(result);
                 if ($(result).find('[name=success]').val()) {
                 location.reload();
                 } else {
                 $(submit_form).html(result);
                 initUserForms();
                 }*/
            });
        });
    }
}


function initUserForms() {
    $('.ajax-userform').each(function (index, el) {
        if (!$(el).is('.init')) {
            var this_form_name = $(el).attr('name');
            var name_merged_form = false;
            if ($('form[name=' + this_form_name + ']').length > 1 && $(el).data('merge') == true) {
                initSubmitMerged(el);
            }
            else {
                initSubmitRegular(el);
            }

        }
        $(el).addClass('init');

        $(el).find(".clean-on-submit").each(function (i, field) {
            $(field).val('');
        });

    });
}

function initDestroy(submit_form) {
    if ($(submit_form).is('.validate')) {
        $(submit_form).removeData("validator");
        $(submit_form).unbind('submit');
    }
    else {
        $(submit_form).off('submit');
    }
    $(submit_form).removeClass('init');
}


function checkMergeFormsSuccess(submit_form, result) {
    var this_form_name = $(submit_form).attr('name');
    if ($(result).find('[name=success]').val() || $(result).filter('[name=success]').val()) {
        $.each(document.merged_forms_cache, function (i, merge_form) {
            if ($(merge_form['form']).is($(submit_form))) {
                merge_form['success'] = 'Y';
                if ($(submit_form).is('.result-redirect') || $(submit_form).is('.result-redirect-timeout')) {
                    var result_id = $(result).find('[name=result_id]').val();
                    if (!result_id) {
                        result_id = $(result).filter('[name=result_id]').val();
                    }

                    merge_form['result_id'] = result_id;
                }
            }
        });
    }
    var success_count = 0;
    $.each(document.merged_forms_cache, function (i, merge_form) {
        if (this_form_name == merge_form['name'] && merge_form['success'] == 'Y') {
            success_count++;
        }
    });

    return success_count == $('form[name=' + this_form_name + ']').length;
}

function checkMergeFormsValid(submit_form) {
    var this_form_name = $(submit_form).attr('name');
    var valid_count = 0; //
    var $other_merge_forms = $('form[name=' + this_form_name + ']').not(submit_form);
    $other_merge_forms.each(function (i, e) {
        if ($(e).valid()) {
            valid_count++;
        }
    });
    return $other_merge_forms.length == valid_count;
}


function setSuccessMergeForms(submit_form, result) {
    var this_form_name = $(submit_form).attr('name');
    var $other_merge_forms = $('form[name=' + this_form_name + ']').not(submit_form);
    $other_merge_forms.each(function (i, e) {
        $(e).html('');
    });

    $(submit_form).html(result);
}

function mergeFormsResultRedirect(submit_form) {
    function do_redirect() {
        console.log('lahi');
        var this_form_name = $(submit_form).attr('name');
        var result_ids = [];
        $.each(document.merged_forms_cache, function (i, merge_form) {
            if (merge_form['result_id']) {
                result_ids.push(merge_form['result_id']);
            }
        });
        var result_url = $(submit_form).data('success-url') + "?find_id=" + result_ids.join(" | ");
        window.location.href = result_url;
    }

    if (submit_form.is('.result-redirect-timeout')) {
        setTimeout(function () {
            do_redirect()
        }, 500);
    }
    else {
        do_redirect();
    }

}

$(function (e) {
    initUserForms();
    $('form.original').siblings('a.append-form').on('click', function (event) {
        var $form = $(this).siblings('form.original');

        console.log($form);
        var $clone_form = $form.clone();
        var this_form_name = $form.attr('name');
        var form_count = $('form[name=' + this_form_name + ']').length;
        if (form_count == 1) {
            initDestroy($form);
        }
        $clone_form.find('[data-clone-hidden]').each(function (i, e) {
            var this_input_name = $(e).find('[name^=form_]').attr('name');
            $form.find('input[type=text][name=' + this_input_name + ']').on('input', function (event) {
                $(e).find('[name=' + this_input_name + ']').val($(this).val());
            });
            $(e).hide();
        });
        $clone_form.find('[data-clone] input').not('[type=hidden]').each(function (i, e) {
            $(e).val('');
        });
        $clone_form.find('[name^=form_]').each(function (i, e) {
            var this_input_id = $(e).attr('id');
            var clone_id = this_input_id.toString() + '-' + form_count;
            $(e).attr('id', clone_id)
            $(e).siblings('label').attr('for', clone_id);
        });

        $clone_form.insertAfter($form.parent().find('form:last'));
        $clone_form.removeClass('init');
        $clone_form.removeClass('original');
        $clone_form.data('merge', true);
        $form.data('merge', true);
        initUserForms();
    });
    $('button[data-submit-form]').on('click', function (e) {
        var form_name = $(this).data('submit-form');
        $('form[name=' + form_name + ']').submit();
    });

    $('.add-file-hidden').on('click', function (e) {
        e.preventDefault();
        $(this).parents('.field').prev('.custom_upload').find('input[type=file]').click();
    });

//    if ($('.js-service-slider').length) {
//        setTimeout(function() {
//            $('.js-service-slider').bxSlider({
//                minSlides: 3,
//                maxSlides: 3,
//                slideMargin: 22,
//                slideWidth: $(this).width() / 3 - 22,
//                pager: false,
//                nextText: '',
//                prevText: '',
//                nextSelector: $('#js-service-slider').find('.next'),
//                prevSelector: $('#js-service-slider').find('.prev'),
//                hideControlOnEnd: true,
//                infiniteLoop: false
//            });
//            /*$('.js-service-slider li').each(function () {
//             var liH = $(this).height();
//             $(this).attr('data-h', liH);
//             if(liH/360 < 1.5){
//             $('.js-service-slider').parent().height(liH);
//             }
//             console.log(liH);
//             });*/
//        }, 100);
//    }

    var sticky = $('.sticky');
    if (sticky.length > 0) {
        var stickyTop = sticky.offset().top,
            stickyLeft = sticky.offset().left;


    }
    $('.main-container').scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.scroll-to-top').addClass('visible');
        }
        else {
            $('.scroll-to-top').removeClass('visible');
        }
        if (sticky.length > 0) {
            var scroll = $(this).scrollTop();
            if (scroll >= stickyTop) {
                sticky.css({
                    left: stickyLeft,
                    top: stickyTop - 110
                });
                sticky.addClass('fixed');
            }
            else {
                sticky.removeClass('fixed');
            }
        }
    });

    $('.scroll-to-top').on('click', function () {
        $('.main-container').animate({
            scrollTop: 0
        }, 300);
    });

});

$(window).load(function () {
    if ($('.video-block').length > 0) {

        $('.video-item').each(function () {
            var $this = $(this);
            if ($this.is('.big')) {
                var height = $this.height() - 3;
                $this.nextUntil('.big').each(function () {
                    $(this).height(height / 2)
                });
            }
        });
    }
});



$(document).ready(function () {

$.each($('.show-all-brands-js a'), function() {
  var text = $(this).data('slide-start-text');
  $(this).html(text);
});

$(".show-all-brands-js a").on("click", function(e) {
    e.preventDefault();
     $(this).parents('.brands-wrapper').find('.hidden-brands-s').slideToggle();

    $(this).toggleClass('show');
    var text = ($(this).hasClass('show')) ? $(this).data('slide-finish-text') : $(this).data('slide-start-text');
    $(this).html(text);
  });
});