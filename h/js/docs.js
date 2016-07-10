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
    function formLink() {
        var documents = [],
            zipLink;

        $('.documentation_page .item input[name="document"]:checked').each(function(i) {
            var fileLink = $(this).attr('data-file-id');
            documents.push(fileLink);
        });

        zipLink = '/arch/?qzip=' + documents.join(',');

        $(".icon-doc-sc").attr({href: zipLink});
    }

    $('.doc-loadlist .head_sub input').click(function (e) {
        console.log(e.target.tagName);
        console.log(e.currentTarget.tagName);
        console.log(e);
        $('+.collapse', $(this).parents('.head_sub'))
          .find('.item input[type="checkbox"]')
          .prop("checked", $(this).prop("checked"))
        ;
        e.stopPropagation();
        formLink();
    });

    $('.doc-loadlist .item input[type="checkbox"]').click(function () {
        formLink();
    });

    $('.doc-loadlist .item .download-link').click(function (e) {
        e.stopPropagation();
        e.preventDefault();
        var $field = $(this).parents('.checkbox');
        if($('input', $field).length){
            window.open($('input', $field).val(), '_blank');
        }
    });
});