/**
 * Created by gillbeits on 29/09/2016.
 */
var DataTableOptions = {
  info: false,
  searching: false,
  paging: false,
  responsive: true,
  fixedHeader: true,
  select: {
    style:    'multi',
    selector: 'td:nth-child(2)'
  },
  columnDefs: [
    {
      orderable: false,
      className: 'select-checkbox',
      targets:   1
    },
    {
      render: $.fn.dataTable.render.number(' ', '.', 2, '', ''),
      targets: 't-money'
    },
    {
      data: 'measure',
      targets: 'measure',
      orderable: false
    }
  ]
};

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

function getSelectedOrderRows (element) {
  var rows = [];
  $(element).parents('#order-edit').find('[data-table]').each(function (i, t) {
    rows = $.merge(rows, $($(t).data('datatables').rows('.selected').ids()).toArray());
  });
  return rows;
}

function tableBinds(table, element) {
  $(element).data('datatables', table);

  table.on( 'select', function ( e, dt, type, indexes ) {
    if ( type === 'row' ) {
      $(table.nodes()).parents('#order-edit').addClass('selected');
    }
  } );

  table.on( 'deselect', function ( e, dt, type, indexes ) {
    if ( type === 'row' ) {
      var rows = getSelectedOrderRows(e.target);
      if (!rows.length) {
        $(e.target).parents('#order-edit').removeClass('selected');
      }
    }
  } );
}

$(function () {

  function initQuickAdd (node) {
    var quickAdd = $('.quick-order-add', node);
    quickAdd.typeahead({
      minLength: 1,
      highlight: true,
      hint: true
    }, {
      name: 'order-quick',
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

  $(initQuickAdd(document)).on('typeahead:select', '.quick-order-add', function (e, item) {
    $(e.target).data('sku', item.sku);
    $(e.target).data('xml_id', item.xml_id);
    $.proxy(new Function($(this).attr('ontypeaheadselect')), this).call(this, e, item);
  });

  $('[data-table]').each(function (i, t) {
    tableBinds($(t).DataTable(DataTableOptions), t);
  });

  Hogart_Lk.stickMenu("[data-stick-parent]");

  $('#order-edit').on('hogart.lk.ajaxDataAppend', function (e, node) {
    $(node).removeClass('selected');
    $('[data-table]', node).each(function (i, t) {
      tableBinds($(t).DataTable(DataTableOptions), t);
    });
    initQuickAdd (node);
    $('[data-change-apply]').changeApply();
  });

  if (order_id) {
    var eventSource = new EventSource("/account/_sse/?action=edit_order&order_id=" + order_id);
    eventSource.addEventListener("message", function (event) {
      var type = event.type;
      if (type === "message") {
        var message;
        try {
          message = JSON.parse(event.data);
        } catch (exception) {

        }
        if ($.isPlainObject(message)) {
          switch (message.type) {
            case 'edit_order_cancel':
              if (!message.order_id) {
                document.location = "/account/order/" + order_id;
              }
              break;
          }
        }
      }
    });
  }
});