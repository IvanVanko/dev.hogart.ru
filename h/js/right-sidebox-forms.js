$(document).ready(function () {
    var messages = {
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
    var isEmail = function (email) {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            return re.test(email);
        },
        isEmpty = function (string) {
            return string.length > 0;
        },
        isPhone = function (phone) {
            return phone.length >= 17;
        };
    $('.js-validation-form-new').submit(function (e) {
        $(this).find('.js-validation-empty select').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Заполните, пожалуйста, это поле']});
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
                    'data-error': messages[$('head').lang]['Заполните, пожалуйста, это поле']
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
                    if (!isEmpty($(this).val())) {
                        e.preventDefault();
                        $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Заполните, пожалуйста, это поле']});
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
                if (!$(this).parent().hasClass('not')) {
                    if (!isEmpty($(this).val())) {
                        e.preventDefault();
                        $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Заполните, пожалуйста, это поле']});
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
                if (!isEmpty($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Заполните, пожалуйста, это поле']});
                }
                else {
                    $(this).parent().removeClass('error')
                }
            });
        }
        else {
            $(this).find('.js-validation-empty textarea').each(function () {
                $(this).val(jQuery.trim($(this).val()));
                if (!isEmpty($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Заполните, пожалуйста, это поле']});
                }
                else {
                    $(this).parent().removeClass('error')
                }
            });
        }


        $(this).find('.js-validation-phone .inputtext').each(function () {
            $(this).val(jQuery.trim($(this).val()));
            if (!isEmpty($(this).val())) {
                e.preventDefault();
                $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Заполните, пожалуйста, это поле']});
            }
            else {
                if (!isPhone($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Заполните это поле правильно']});
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
                $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Заполните, пожалуйста, это поле']});
            }
            else {
                if (!isEmail($(this).val())) {
                    e.preventDefault();
                    $(this).parent().addClass('error').attr({'data-error': messages[$('head').lang]['Введите настоящий E-mail']});
                }
                else {
                    $(this).parent().removeClass('error')
                }
            }
        });
        return this;
    });

});