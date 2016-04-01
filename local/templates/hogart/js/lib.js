$(function () {
    $(document).on('keypress', '[data-digits-only]', function (event) {
        var char = getChar(event);
        if (char === null) {
            return true;
        }

        if (!(/\d|,/.test(char)) || ($(this).val().indexOf(",") >= 0 && char == ',')) {
            return false;
        }
    });

    $('[data-phone-mask], .js-validation-phone input, input.js-validation-phone').mask("+7 (999) 999-9999", {
        placeholder: "+7 (___) ___-____"
    });

    if("fancybox" in $){
        $('[data-fancybox]').fancybox({
            wrapCSS: 'fancybox-popup',
            padding: 0,
            helpers: {
                overlay: {
                    locked: false
                }
            }
        });
    }

    $(document).on('click change', '[data-submit-target]', function (e) {
        var $this = $(this);
        if ($this.data('not-click') == true && e.type == 'click') {
            return;
        }
        setTimeout(function () {
            $("." + $this.data('submit-target')).trigger('submit');
        }, 50);
    });

    $('[data-ajax-form]').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this),
            data = (window.FormData === undefined ? $form.serialize() : new FormData($form[0])),
            $fieldsBlock = $('[data-fields]', $form),
            $messageBlock = $('[data-form-message]', $form);

        $form.trigger('forms.submit.before', [data]);
        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    if ($form.data('slide-enable') == true) {
                        $fieldsBlock.slideUp();
                    }
                    $form[0].reset();

                    $form.trigger('forms.submit.success', [data]);

                    if ($form.data('target')) {
                        var currentModal = $form.parents('.modal[role="dialog"]');
                        if (currentModal.length) {
                            currentModal.modal('hide');
                        }
                        $($form.data('target')).modal('show');
                    }
                }
                else {
                    $form.trigger('forms.submit.error', [data]);
                }
                if (data.message && $messageBlock) {
                    var place = $('[data-place-text]', $messageBlock);
                    var holder = $('[data-text-holder]', $messageBlock);

                    if (holder) {
                        if (data.success) {
                            holder.addClass('success-msg').removeClass('text-error');
                        }
                        else {
                            holder.addClass('text-error').removeClass('success-msg');
                        }
                    }
                    if (place) {
                        place.html(data.message);
                    }
                    else {
                        $messageBlock.html(data.message);
                    }
                    if ($form.data('slide-enable') == true) {
                        $messageBlock.slideDown();
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $form.trigger('forms.submit.error', [xhr, textStatus, errorThrown]);
            },
            complete: function (xhr, textStatus) {
                $form.trigger('forms.submit.complete', [xhr, textStatus]);
            }
        });
        return false;
    });
});

// event.type должен быть keypress
function getChar(event) {
    if (event.which == null) {  // IE
        if (event.keyCode < 32) return null; // спец. символ
        return String.fromCharCode(event.keyCode)
    }

    if (event.which != 0 && event.charCode != 0) { // все кроме IE
        if (event.which < 32) return null; // спец. символ
        return String.fromCharCode(event.which); // остальные
    }

    return null; // спец. символ
}

function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires*1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for(var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}


function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

Share = {
    vkontakte: function(purl, ptitle, pimg, text) {
        url = 'http://vkontakte.ru/share.php?';
        url += 'url=' + encodeURIComponent(purl);
        url += '&title=' + encodeURIComponent(ptitle);
        url += '&description=' + encodeURIComponent(text);
        url += '&image=' + encodeURIComponent(pimg);
        url += '&noparse=true';
        Share.popup(url);
    },
    odnoklassniki: function(purl, text) {
        url = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
        url += '&st.comments=' + encodeURIComponent(text);
        url += '&st._surl=' + encodeURIComponent(purl);
        Share.popup(url);
    },
    facebook: function(purl, ptitle, pimg, text) {
        url = 'http://www.facebook.com/sharer.php?';
        url += '&u=' + encodeURIComponent(purl);
        Share.popup(url);
    },
    twitter: function(purl, ptitle) {
        url = 'http://twitter.com/share?';
        url += 'text=' + encodeURIComponent(ptitle);
        url += '&url=' + encodeURIComponent(purl);
        url += '&counturl=' + encodeURIComponent(purl);
        Share.popup(url);
    },
    mailru: function(purl, ptitle, pimg, text) {
        url = 'http://connect.mail.ru/share?';
        url += 'url=' + encodeURIComponent(purl);
        url += '&title=' + encodeURIComponent(ptitle);
        url += '&description=' + encodeURIComponent(text);
        url += '&imageurl=' + encodeURIComponent(pimg);
        Share.popup(url)
    },
    popup: function(url) {
        window.open(url,'','toolbar=0,status=0,width=626,height=436');
    }
};