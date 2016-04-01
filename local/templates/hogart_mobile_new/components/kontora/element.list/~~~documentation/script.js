$(document).ready(function(){
    // $('.icon-doc-sc').click(function(){
    //     var documents = [],
    //         zipLink;
    //     $('.doc-loadlist input[name="document"]:checked').each(function(i) {
    //         var fileLink = $(this).attr('data-file-id');

    //         documents.push(fileLink);
    //     });

    //     zipLink = '/arch/?qzip='+documents.join(',');
    //     setTimeout(function () {
    //         window.open(zipLink, "_blank");
    //     }, 500);

    //     console.log(documents, ' | ', zipLink);
    // });

    //$(".documentation_page .doc-list-box input").bind("click", function () {
    $(".documentation_page .doc-list-box input[name='document']").bind("change", function () {
        var documents = [],
            zipLink;
        
        $('.documentation_page .doc-list-box input[name="document"]:checked').each(function(i) {
            var fileLink = $(this).attr('data-file-id');
            documents.push(fileLink);
        });

        zipLink = '/arch/?qzip=' + documents.join(',');

        $(".icon-doc-sc").attr({href: zipLink});
    });
});