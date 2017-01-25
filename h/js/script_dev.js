window.addEventListener("load", function(event) {

    var ul = document.querySelector('ul.main-navigation');
    var li = ul.childNodes;


    function handle_show(e) {
        var elem = e.target.closest('li');
        if( elem.querySelector('.navigation-sub-menu') && !elem.classList.contains('show') ) {
            elem.classList.add('show');
        } else {
            elem.classList.remove('show');
        }
    }

    for( var i = 0; i < li.length; i++) {
        li[i].addEventListener('click', handle_show);
    }

});

window.addEventListener("load", function(event) {

    var input = document.querySelector('.header-mobile__search-input');
    var block_search = document.querySelector('.header-mobile__search');

    function handle_focus(e) {
        if( block_search.classList.contains('focus')) {
            block_search.classList.remove('focus');
        } else {
            block_search.classList.add('focus');
        }
    }

    input.addEventListener('focus', handle_focus);
    input.addEventListener('focusout', handle_focus);

});