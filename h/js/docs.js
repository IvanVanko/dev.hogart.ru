$(document).ready(function () {

    function toggleDoc(element) {
        element.toggleClass('active');
        element.parent().find('.item-box').slideToggle().toggleClass('active');
    }

    function checkCheckedSections($el){
        var parent = $el.parents('.item-box'),
            section = $el.parents('.doc-box');

        if(parent.find(".item :checked").length === 0){
            parent.find(".head_sub input").prop('checked', false);
        }

        if(section.find(".head_sub :checked, .item :checked").length === 0){
            section.find(".head input").prop('checked', false);
        }
    }
    function formLink(){
        var documents = [],
            zipLink;

        $('.documentation_page .item input[name="document"]:checked').each(function(i) {
            var fileLink = $(this).attr('data-file-id');
            documents.push(fileLink);
        });

        zipLink = '/arch/?qzip=' + documents.join(',');

        $(".icon-doc-sc").attr({href: zipLink});
    }

    $('.doc-loadlist .head, .doc-loadlist .head_sub').click(function (e) {
        if ($(this).hasClass('head')) {
            toggleDoc($(this));
        }
        else if ($(this).hasClass('head_sub')) {
            $(this).toggleClass('active');
            $(this).parent().find('.item').slideToggle();
        }
    });

    $('.doc-loadlist .head span').click(function (e) {
        e.stopPropagation();
        e.preventDefault();

        var $parent = $(this).parents('.li-container'),
            $list = $(this).parents('.doc-loadlist');

        var enabled = !$parent.find('input').prop("checked");

        $parent.find('input').prop({
            "checked": enabled
        });

        $list.find('.item-box input').prop('checked', enabled);
        formLink();
    });

    $('.doc-loadlist .head label, .doc-loadlist .head label').click(function (e) {
        toggleDoc($(this));
    });

    $('.doc-loadlist .head_sub span').click(function (e) {
        e.stopPropagation();
        e.preventDefault();

        var $this = $(this),
            $field = $this.parents('.field'),
            $list = $this.parents('.item-box').find('.item'),
            checked = !$field.find('input').prop("checked");

        $this.toggleClass('active');
        $field.find('input').prop("checked", checked);
        $list.find('input').prop("checked", checked);
        formLink();
    });

    $('.doc-loadlist .head h4').click(function (e) {
        var $parent = $(this).parents('.li-container');
        $parent.find('.item').slideToggle();
    });

    $('.doc-loadlist li IMG').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        var $field = $(this).parents('.field ');
        if($('input', $field).length){
            window.open($('input', $field).val(), '_blank');
        }
    });

    $('.doc-loadlist .doc-download-link .icon-acrobat').on('click', function () {
        var link = $(this).find('input');
        if (link.length == 0) {
            link = $(this).parents('.custom_checkbox').find('input[name="document"]');
        }
        window.open(link.val(), '_blank');
    });

    $('.doc-loadlist .doc-download-link .fake-checkbox').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();

        var checkbox = $(this).parents('.custom_checkbox').find('input[name="document"]');
        checkbox.prop('checked', !checkbox.prop('checked'));
        checkbox.trigger('change');
        formLink();
        checkCheckedSections($(this));
    });
});