/**
 * Created by gillbeits on 15/09/16.
 */
$.fn.fileinput.defaults.ajaxSettings = $.extend($.fn.fileinput.defaults.ajaxSettings, {
  headers: {
    'BX-AJAX': true
  }
});

var DataTableOptions = {
  info: false,
  searching: false,
  paging: false,
  responsive: true,
  rowReorder: {
    selector: 'td:nth-child(2)'
  },
  fixedHeader: true,
  buttons: [
    'copy', 'excel', 'pdf'
  ],
  select: {
    style:    'multi',
    selector: 'td:nth-child(3)'
  },
  columnDefs: [
    {
      orderable: false,
      className: 'order-icon',
      targets:   1
    },
    {
      orderable: false,
      className: 'select-checkbox',
      targets:   2
    },
    {
      render: $.fn.dataTable.render.number(' ', '.', 2, '', ''),
      targets: 't-money'
    },
    {
      render: $.fn.dataTable.render.number('', '', 0, '', '&#37;'),
      targets: 't-percent'
    },
    {
      data: 'measure',
      targets: 6,
      orderable: false
    }
  ]
};

function openLinkAddItemsDialog (e, dialogInstance, link) {
  var $fileInput = $(':file', dialogInstance.$modal);
  var fn = new Function($(link).data('link'));

  $(dialogInstance.$modal).off('submit', 'form');
  $('form', dialogInstance.$modal).validator().off('submit');

  $('form', dialogInstance.$modal).on('submit', function (_e) {
    if (!_e.isDefaultPrevented()) {
      var form = this;
      $fileInput
        .off('filebatchuploadsuccess')
        .on('filebatchuploadsuccess', function (events, data) {
          $fileInput.data('files', null);
          var files = [];
          data.response.initialPreviewConfig.forEach(function (file) {
            files.push(file.url);
          });
          $fileInput.data('files', files);
          fn.call(form);
          dialogInstance.close();
        });

      $fileInput.fileinput("upload");
    }
    _e.preventDefault();
    return true;
  });

  dialogInstance.$modal
    .off('hogart.lk.ajaxurlchange')
    .on('hogart.lk.ajaxurlchange', function (e, a) {
      e.stopPropagation();
      var files = $fileInput.data('files');
      files.forEach(function (v, i) {
        a.search += "&file[" + i + "]=" + v;
      });
      $('input:not([data-switch]), select', dialogInstance.$modal).each(function (i, el) {
        a.search += "&" + $(el).attr('name') + "=" + $(el).val();
      });
    });
}

function openLinkAddOrderDialog (e, dialogInstance, link) {

  $(':input', dialogInstance.$modal).each(function (i, el) {
    $(el).val($(el).defaultValue);
  });

  dialogInstance.$modal
    .off('hogart.lk.ajaxurlchange')
    .on('hogart.lk.ajaxurlchange', function (e, a) {
      e.stopPropagation();
      $('input[data-switch]:checked', dialogInstance.$modal).each(function (i, el) {
        a.search += "&" + $(el).attr('name') + "=" + (+$(el).is(':checked'));
      });
      $('textarea', dialogInstance.$modal).each(function (i, el) {
        a.search += "&" + $(el).attr('name') + "=" + $(el).val();
      });
    });
}

function openLinkEditItemGroupDialog (e, dialogInstance, link) {
  $('input', dialogInstance.$modal).each(function (i, el) {
    $(el).val($(el).defaultValue);
  });

  dialogInstance.$modal
    .off('hogart.lk.ajaxurlchange')
    .on('hogart.lk.ajaxurlchange', function (e, a) {
      e.stopPropagation();
      getSelectedCartRows(link).forEach(function (v, i) {
        a.search += "&item[" + i + "]=" + v;
      });
      $('input:not([data-switch]), select', dialogInstance.$modal).each(function (i, el) {
        a.search += "&" + $(el).attr('name') + "=" + $(el).val();
      });
      $('input[data-switch]', dialogInstance.$modal).each(function (i, el) {
        a.search += "&" + $(el).attr('name') + "=" + (+$(el).is(':checked'));
      });
    });
}

function getSelectedCartRows (element) {
  var rows = [];
  $(element).parents('[data-cart]').find('[data-table]').each(function (i, t) {
    rows = $.merge(rows, $($(t).data('datatables').rows('.selected').ids()).toArray());
  });
  return rows;
}

function tableBinds(table, element) {
  $(element).data('datatables', table);

  table.on('row-reordered', function (e, diff, edit) {
    if (Object.keys(edit.values).length > 1) {
      window['changeTableOrder'] = function () {
        return edit.values;
      };
      $(this).one('onrowreordered', new Function($(this).attr('onrowreordered')));
      $(this).trigger('onrowreordered');
    }
  });

  table.on( 'select', function ( e, dt, type, indexes ) {
    if ( type === 'row' ) {
      $(table.nodes()).parents('[data-cart]').addClass('selected');
    }
  } );

  table.on( 'deselect', function ( e, dt, type, indexes ) {
    if ( type === 'row' ) {
      var rows = getSelectedCartRows(e.target);
      if (!rows.length) {
        $(e.target).parents('[data-cart]').removeClass('selected');
      }
    }
  } );
}

function quickCartAddSuggest (query, syncResults, asyncResults) {

  if ($.isPlainObject(window.quickCardAddRequest)) {
    window.quickCardAddRequest.abort();
    window.quickCardAddRequest = null;
  }
  setTimeout(function () {
    window.quickCardAddRequest = $.ajax( { url: "/account/cart/?search=" + query, dataType: 'json', type: 'post' } );
    window.quickCardAddRequest.then( function ( data ) {
      if ($.isArray(data.hits.hits)) {
        var source = [];
        data.hits.hits.forEach(function (item) {
          source.push( item._source );
        });
        asyncResults(source);
      }
    } );
  }, 300)
}

$(function () {

  function initQuickAdd (node) {
    var quickAdd = $('.quick-cart-add', node);
    quickAdd.typeahead({
      minLength: 1,
      highlight: true,
      hint: true
    }, {
      name: 'cart-quick',
      limit: 10,
      async: true,
      source: quickCartAddSuggest,
      display: function (item) { return "(" + item.sku + ") " + item.title;  },
      templates: {
        empty: [
          '<div class="empty-message">',
          'Товары не найдены',
          '</div>'
        ].join('\n'),
        suggestion: Handlebars.compile('<div><b><small>({{sku}})</small></b> {{title}}</div>')
      }
    });

    return node;
  }

  $(initQuickAdd(document)).on('typeahead:select', '.quick-cart-add', function (e, item) {
    $(e.target).data('sku', item.sku);
    $.proxy(new Function($(this).attr('ontypeaheadselect')), this).call(this, e, item);
  });

  window.Hogart_Lk.createAjaxObserver('#carts', function (MutationRecords) {
    MutationRecords.forEach(function (record) {
      $('.selectpicker', record.target).selectpicker();
    });
  });

  $('[data-table]').each(function (i, t) {
    tableBinds($(t).DataTable(DataTableOptions), t);
  });

  Hogart_Lk.stickMenu($('#carts')).on('hogart.lk.ajaxDataAppend', function (e, node) {
    $('[data-cart]', node).removeClass('selected');
    $(node).removeClass('selected');
    $('[data-table]', node).each(function (i, t) {
      tableBinds($(t).DataTable(DataTableOptions), t);
    });
    initQuickAdd (node);
    $('[data-change-apply]').changeApply();
    Hogart_Lk.stickMenu(node);
  });
});