/**
 * Created with JetBrains PhpStorm.
 * User: alexander
 * Date: 25.09.14
 * Time: 19:12
 * To change this template use File | Settings | File Templates.
 */
$(function () {
    function getUrlParameters(parameter, staticURL, decode){
        /*
         Function: getUrlParameters
         Description: Get the value of URL parameters either from
         current URL or static URL
         Author: Tirumal
         URL: www.code-tricks.com
         */
        var currLocation = (staticURL.length)? staticURL : window.location.search,
            parArr = currLocation.split("?")[1].split("&"),
            returnBool = true;

        for(var i = 0; i < parArr.length; i++){
            parr = parArr[i].split("=");
            if(parr[0] == parameter){
                return (decode) ? decodeURIComponent(parr[1]) : parr[1];
                returnBool = true;
            }else{
                returnBool = false;
            }
        }

        if(!returnBool) return false;
    }
    var Filter = '';
    function initPopups ($parent_element) {
        $parent_element.find('.magnific-popup').each(function (ind, elem) {
            $(elem).magnificPopup({
                type: 'ajax',
                ajax: {
                    settings: {
                        type: 'post',
                        data: {
                            mode: 'detail',
                            ID: $(elem).data('id')
                        }
                    }
                },
                callbacks: {
                    ajaxContentAdded: function() {
                        // Ajax content is loaded and appended to DOM
                        console.log(this.content);
                    }
                }
            });
        });
    }
    initPopups($('#ajax-claims'));
    $('body').on('click','.claim-table-row', function (e) {
        //console.log($(".magnific-popup[data-id"+$(this).data('id')+"]"));
        $(".magnific-popup[data-id="+$(e.currentTarget).data('id')+"]").trigger('click');
    });
    /*$('.claim-table-row').on('click', function (e) {
        console.log(e);
        console.log(e.currentTarget);
        console.log(e.target);
        //console.log($(".magnific-popup[data-id"+$(this).data('id')+"]"));
        $(".magnific-popup[data-id="+$(e.currentTarget).data('id')+"]").trigger('click');
    });*/
    $(document).on('click', '.cancel.close',function (e) {
        $.magnificPopup.close();
    });
    $(document).on('click', '.preload-over',function (e) {
        return false;
    });
    $(document).on('click', '.decline, .accept',function (e) {
        var $preloader = $('<div class="preload-over"><div class="preloader"></div></div>');
        var $message = $('<tr class="claim-table-row"><td class="error" colspan="3"></td></tr>');
        $preloader.insertBefore($(this).parents('#messages_inbox_edit_table'));
        console.log($('<div class"preload-over"></div>'));
        var $button = $(this);
        $.ajax({
            type: 'post',
            data: {
                ID: $(e.target).parents('.messages-detail').data('id'),
                mode: 'update',
                'UF_STATUS': $(e.target).data('status')
            },
            dataType: 'json',
            url: $('#popup-anchor').attr('href'),
            success: function (res) {
                $message.find('td').text(res['message']);
                $preloader.remove();
                $('.claim-table-row .error').remove();
                if (!res['success']) {
                    $message.insertAfter($button.parents('table').find('tr.heading'));
                    $preloader.remove();
                } else {
                    $.magnificPopup.close();
                    document.location.reload();
                }
            },
            error: function (xhr, status, err_mess) {
                console.log(err_mess);
            }
        });
    });
    $('#claims-filter select').selectize({
        plugins: ['remove_button'],
        persist: false,
        search: false,
        create: true,
        render: {
            item: function(data, escape) {
                return '<div>"' + escape(data.text) + '"</div>';
            }
        }
        /*onDelete: function(values) {
            console.log($(this).prop('$input').val());
            return $(this).prop('$input').val().length != 1;
        }*/
    });
    $('#claims-filter').on('submit', function (e) {
        e.preventDefault();
        $('.loader-filter').css('display','inline-block');
        var $form = $(this);
        Filter = $form.serialize();
        //console.log($form.serialize());
        $.ajax({
            url: $form.attr('action'),
            data: Filter,
            dataType: 'html',
            type: $form.attr('method'),
            success: function (res) {
                var $ajax_result_object = $(res);
                $('#ajax-claims').html('');
                $ajax_result_object.appendTo($('#ajax-claims'));
                initPopups($('#ajax-claims'));
                $('.loader-filter').hide();
            },
            error: function (xhr, stat, err_mess) {
                console.log(err_mess);
            }
        });
    });
    $('body').on('click','ul.claims-pager a', function (e) {
        e.preventDefault();
        $('#claims-filter .hidden_page').val(getUrlParameters('PAGEN_1',$(this).attr('href'), false));
        $('#claims-filter').trigger('submit');
    });
});