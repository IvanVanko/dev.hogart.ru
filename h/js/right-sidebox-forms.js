$(document).ready(function () {
    //console.log('+');
    var isEmail = function (email) {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            //re = /\S+@\S+\.\S+/;

            //var re = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
            return re.test(email);
        },
        isEmpty = function (string) {
            return string.length > 0;
        },
        isPhone = function (phone) {
            //var re = /^\d[\d\(\)\ -]{4,14}\d$/;
            //return re.test(phone);
            return phone.length >= 17;
        };
    var sumbitFlag = false;
    $('.js-validation-form-new').submit(function (e) {
        /*$(this).find('.js-validation-empty').each(function () {
         $(this).val(jQuery.trim($(this).val()));
         console.log($(this), $(this).val());
         if (!isEmpty($(this).val())) {
         e.preventDefault();
         $(this).parent().addClass('error').attr({'data-error': 'Заполните, пожалуйста, это поле'});
         }
         else {
         $(this).parent().removeClass('error')
         }
         });*/
        $(this).find('.js-validation-empty select').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            //console.log($(this), $(this).val());
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({'data-error': 'Заполните, пожалуйста, это поле'});
            }
            else {
                $(this).parent().removeClass('error')
            }
        });

        $(this).find('.js-validation-empty .inputfile').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            console.log($(this), $(this).val());
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({
                    'data-error': 'Заполните, пожалуйста, это поле'
                });
            }
            else {
                $(this).parent().removeClass('error')
            }
        });

        if ($(this).find('.custom_label .inputtext').length) {
            $(this).find('.custom_label .inputtext').each(function () {
                if (!$(this).parent().hasClass('not')) {

                    $(this).val(jQuery.trim($(this).val()));
                    //console.log($(this), $(this).val());
                    if (!isEmpty($(this).val())) {
                        e.preventDefault();
                        $(this).parent().addClass('error').attr({'data-error': 'Заполните, пожалуйста, это поле'});
                    }
                    else {
                        $(this).parent().removeClass('error')
                    }
                }
            });
        }
        else {
            $(this).find('.custom_label input').each(function () {
                $(this).val(jQuery.trim($(this).val()));
                //console.log($(this), $(this).val());
                if (!$(this).parent().hasClass('not')) {
                    if (!isEmpty($(this).val())) {
                        e.preventDefault();
                        $(this).parent().addClass('error').attr({'data-error': 'Заполните, пожалуйста, это поле'});
                    }
                    else {
                        $(this).parent().removeClass('error')
                    }
                }
            });
        }
        if ($(this).find('.js-validation-empty .inputtextarea').length) {
            $(this).find('.js-validation-empty .inputtextarea').each(function () {
                $(this).val(jQuery.trim($(this).val()));
                //console.log($(this), $(this).val());
                if (!isEmpty($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({'data-error': 'Заполните, пожалуйста, это поле'});
                }
                else {
                    $(this).parent().removeClass('error')
                }
            });
        }
        else {
            $(this).find('.js-validation-empty textarea').each(function () {
                $(this).val(jQuery.trim($(this).val()));
                //console.log($(this), $(this).val());
                if (!isEmpty($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({'data-error': 'Заполните, пожалуйста, это поле'});
                }
                else {
                    $(this).parent().removeClass('error')
                }
            });
        }


        $(this).find('.js-validation-phone .inputtext').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            //console.log($(this), $(this).val());
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({'data-error': 'Заполните, пожалуйста, это поле'});
            }
            else {
                if (!isPhone($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({'data-error': 'Заполните это поле правильно'});
                }
                else {
                    $(this).parent().removeClass('error')
                }
            }
        });

        $(this).find('.js-validation-email .inputtext').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            //console.log($(this), $(this).val());
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({'data-error': 'Заполните, пожалуйста, это поле'});
            }
            else {
                if (!isEmail($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({'data-error': 'Введите настоящий E-mail'});
                }
                else {
                    $(this).parent().removeClass('error')
                }
            }
        });
        return this;
    });

});