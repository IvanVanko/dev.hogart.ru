/**
 * Created by gillbeits on 11/11/2016.
 */

$(function () {
    function headerSearchSuggest (url, ajaxKey) {
        return function (query, syncResults, asyncResults) {
          if (window.headerSearchSuggestTimer) {
            clearTimeout(window.headerSearchSuggestTimer);
          }
          window.headerSearchSuggestTimer = setTimeout(function () {
              if ($.isPlainObject(window.headerSearchSuggestRequest)) {
                window.headerSearchSuggestRequest.abort();
                window.headerSearchSuggestRequest = null;
              }

              window.headerSearchSuggestRequest = $.ajax( { url: url + "?q=" + query, data: { ajaxKey: ajaxKey }, dataType: 'json', type: 'post' } );
              window.headerSearchSuggestRequest.then( function ( data ) {
                  if ($.isArray(data.hits.hits)) {
                      var source = [];
                      data.hits.hits.forEach(function (item) {
                          source.push( item._source );
                      });
                      asyncResults(source);
                  }
              } );
            }, 300)
        };
    }

    function initHeaderSearch (node) {
        var quickAdd = $('#header-search input[type="text"]', node);
        quickAdd.typeahead({
            minLength: 1,
            highlight: true,
            hint: true
        }, {
            name: 'header-search',
            limit: 20,
            async: true,
            source: headerSearchSuggest(quickAdd.data('suggestUrl'), quickAdd.data('suggestAjaxKey')),
            display: function (item) { return item.title },
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'Поиск ничего не дал',
                    '</div>'
                ].join('\n'),
                suggestion: Handlebars.compile('<div class="suggest-item">' +
                    '<div>{{#if sku}}<b><small>{{sku}}</small></b>{{/if}} {{title}}</div>' +
                    '<div class="suggest-footer">' +
                    '   <i><small>{{block}}</small></i>' +
                    '</div>' +
                '</div>'
                )
            }
        }).on('typeahead:select', function (e, item) {
            document.location = item.url;
        });

        return node;
    }

    initHeaderSearch(document);
});