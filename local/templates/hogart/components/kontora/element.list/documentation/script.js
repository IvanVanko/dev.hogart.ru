$(document).ready(function(){
    $(window).on('resize', function(){
        $('.row.sticky').width($('.doc-list-box ').width());
    });
});