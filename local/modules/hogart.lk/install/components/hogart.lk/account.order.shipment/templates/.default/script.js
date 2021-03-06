/**
 * Created by gillbeits on 01/10/2016.
 */
var addresses = {};

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
      data: 'quantity',
      targets: 4,
      orderable: false
    },
    {
      data: 'measure',
      targets: 5,
      orderable: false
    },
    {
      render: $.fn.dataTable.render.number(' ', '.', 2, '', ''),
      targets: [6, 8]
    },
    {
      data: 'price',
      column: 'price',
      render: $.fn.dataTable.render.number(' ', '.', 2, '', ''),
      targets: [7]
    },
    {
      data: 'total',
      column: 'total',
      render: $.fn.dataTable.render.number(' ', '.', 2, '', ''),
      targets: [9]
    }
  ]
};

$.fn.validator.Constructor.INPUT_SELECTOR = 'fieldset:not(:disabled):not(:hidden) :input:not([type="hidden"], [type="submit"], [type="reset"], button)';

window.Hogart_Lk.createAjaxObserver('form[name="add-rtu"] fieldset', function (MutationRecords) {
  MutationRecords.forEach(function (record) {
    if ($(record.target).is('fieldset') && record.type == 'attributes' && record.attributeName == 'disabled') {
      var form = $(record.target).parents('form');
      form.validator('update');
    }
  });
}, { childList: false, characterData: false, attributes: true, subtree: false });

$(function () {
  window.DaData.init_fio(
    $('input[name="new_last_name"]'),
    $('input[name="new_name"]'),
    $('input[name="new_middle_name"]')
  );

  $('[name="plan_date"]').datetimepicker({
    locale: 'ru',
    format: 'L',
    useCurrent: false,
    useStrict: true,
    daysOfWeekDisabled: [0, 6],
    minDate: moment().add(moment().format('HH') >= 16 ? 2 : 1, 'days')
  });

  $(document).on('input', 'input[name="quantity"]', function (e) {
    var api = $(this).parents('table').dataTable().api();
    var tr = $(this).parents('tr');
    var data = api.row(tr).data();
    var default_count = $(tr).data('defaultCount') || 1;
    var prev_value = $(this).data('value');
    var count = Math[$(this).val() < prev_value ? 'floor' : 'ceil']($(this).val() / default_count) * default_count;
    if (count <= 0) {
      $(this).val(prev_value);
      return;
    }

    if (count > this.defaultValue) {
      $(this).val(prev_value);
      return;
    }

    $(this).val(count);
    $(this).data('value', count);
    api.cell(tr, 9).data(data.price * count).draw();
    $(this).focus();
    $(this).parents('.order-line').trigger('recalculate');
  });

  $('.checkbox-title :input').on('change', function (e) {
    var tables = $(e.target).parents('[data-order]').find('[data-table]');
    var method;
    if ($(e.target).is(':checked')) {
      method = 'select';
    } else {
      method = 'deselect';
    }
    tables.each(function (i, table) {
      $(table).dataTable().api().rows()[method]();
    });
    $(this).parents('.order-line').trigger('recalculate');
  });

  $(document).on('recalculate', '.order-line', function (e) {
    var api = $('table[data-table]', this).dataTable().api();
    var totalOrder = 0, totalContract = 0, totalAccount = 0;

    var maxSaleAccount = parseFloat($('[data-store] [data-account-credit-limit]').data('accountCreditLimit') || 0);
    var maxSaleContract = parseFloat($('[data-contract-credit-limit]', this).data('contractCreditLimit') || 0);
    var maxSaleOrder = parseFloat($('[data-sale-max]', this).data('saleMax') || 0);
    var contractId = $(this).data('contractId');

    $.each(api.rows('.selected').data(), function (i, el) {
      totalOrder += Math.round(el.total * 100) / 100;
    });

    totalOrder = Math.round(totalOrder * 100) / 100;

    $('[data-sale-selected]', this)
      .text($.fn.dataTable.render.number(' ', ',', 2, '', '').display(totalOrder))
      .removeClass('color-danger')
      .removeClass('color-primary')
      .addClass('color-' + (totalOrder > maxSaleOrder ? 'danger' : 'primary'))
      .end()
      .removeClass('sale-granted')
      .removeClass('selected')
      .addClass(api.rows('.selected').count() ? 'selected' : '')
      .addClass((totalOrder > maxSaleOrder || totalOrder == 0 ? '' : 'sale-granted'))
    ;

    if ($(this).data('credit')) {
      $('[data-store] [data-order] table[data-table]').each(function (i, table) {
        var cId = $(table).parents('.order-line').data('contractId');
        var api = $(table).dataTable().api();
        $.each(api.rows('.selected').data(), function (i, el) {
          if (cId == contractId) {
            totalContract += Math.round(el.total * 100) / 100;
          }
          totalAccount += Math.round(el.total * 100) / 100;
        });
      });

      $('[data-store] [data-account-sale-selected]')
        .text($.fn.dataTable.render.number(' ', ',', 2, '', '').display(totalAccount))
        .removeClass('color-danger')
        .removeClass('color-primary')
        .addClass('color-' + (totalAccount > maxSaleAccount ? 'danger' : 'primary'))
      ;

      $('[data-store] [data-contract-id="' + contractId + '"] [data-contract-sale-selected]')
        .text($.fn.dataTable.render.number(' ', ',', 2, '', '').display(totalContract))
        .removeClass('color-danger')
        .removeClass('color-primary')
        .addClass('color-' + (totalContract > maxSaleContract ? 'danger' : 'primary'))
      ;

      if (maxSaleContract - totalContract <= 0 || maxSaleAccount - totalAccount <= 0) {
        $(this).removeClass('sale-granted');
      }

    }

    var btn = $(this).parents('[data-store]').find('[data-rtu-create]');
    if (
      $(this).parents('[data-store]').find('.order-line.selected').length
      && !$(this).parents('[data-store]').find('.order-line.selected:not(.sale-granted)').length
    ) {
      btn.show();
    } else {
      btn.hide();
    }
  });

  $(document).on('change', '[data-switch][name="new_address"]', function (e) {
    $('fieldset[data-new-address]')
      .attr('disabled', 'disabled')
      .hide();

    $('fieldset[data-new-address="' + (this.checked ? 'true' : 'false') + '"]')
      .attr('disabled', null)
      .show();
  });

  $(document).on('change', 'select[name="contact"]', function (e) {
    $('input[name="phone"]').val($('option:selected', this).data('phone'));
    $('input[name="email"]').val($('option:selected', this).data('email'));
  });

  $(document).on('keydown, keyup', 'textarea[name="comment"]', function (e) {
    $(this).parents().find('.char-count').text(Math.max(0, $(this).attr('maxlength') - $(this).val().length));
  });

  $(document).on('change', '[data-switch][name="new_contact"]', function (e) {
    $('fieldset[data-new-contact]')
      .attr('disabled', 'disabled')
    ;
    $('fieldset[data-new-contact="true"]').hide();
    $('fieldset[data-new-contact="' + (this.checked ? 'true' : 'false') + '"]')
      .attr('disabled', null)
      .show();
    if (this.checked) {
      $(this).parents('[data-delivery-type]').css({
        backgroundColor: 'rgba(149,198,0,.1)',
        padding: '10px 0'
      })
    } else {
      $(this).parents('[data-delivery-type]').css({
        backgroundColor: 'transparent',
        padding: '0'
      })
    }
  });

  $(document).on('change', '[data-switch][name="is_tk"]', function (e) {
    $('fieldset[data-tk]')
      .attr('disabled', !this.checked ? "disabled" : null)
      .toggle(this.checked);
  });

  $('[data-switch][name="delivery_type"]').on('change', function (e) {
    $('[data-delivery-type]').removeAttr('disabled').removeClass('active');
    $('[data-delivery-type] fieldset').removeAttr('disabled');
    $('[data-delivery-type]:not([data-delivery-type="' +  $(this).val() + '"])').attr('disabled', 'disabled');
    $('[data-delivery-type]:not([data-delivery-type="' +  $(this).val() + '"]) fieldset').attr('disabled', 'disabled');
    $('[data-delivery-type="' + $(this).val() + '"]').addClass('active');
  });

  $('[data-table]').each(function (i, t) {
    var table = $(t).DataTable(DataTableOptions);

    table.on('select', function ( e, dt, type, indexes ) {
      if ( type === 'row' ) {
        $(e.target).parents('.order-line').trigger('recalculate');
      }
    });
    table.on('deselect', function ( e, dt, type, indexes ) {
      if ( type === 'row' ) {
        $(e.target).parents('.order-line').trigger('recalculate');
      }
    });

  });

  Hogart_Lk.stickMenu($('[data-store]'));

  $('[data-rtu-create]').on('click', function (e) {
    e.preventDefault();
    var id = "add-rtu-dialog";
    var modal = $('[data-remodal-id="' + id + '"]');
    modal = modal.remodal();
    $('form :input', modal.$modal).each(function (_, el) {
      $(el).val(this.defaultValue);
    });
    var tables = $(e.target).parents('[data-store]').find('[data-table]');
    var rows = {};
    tables.each(function (it, table) {
      var api = $(table).dataTable().api();
      api.rows('.selected').nodes().each(function (node, index, api) {
        rows[api.row(node).id()] = {
          order: $(node).data('order'),
          company: $(node).data('company'),
          quantity: $('input[name="quantity"]', node).val()
        };
      });
    });
    $('input[name="rows"]', modal.$modal).val(JSON.stringify(rows));
    var abbr_store = $('<abbr></abbr>');
    $(abbr_store).attr('title', $(e.target).data('store').ADDRESS);
    $(abbr_store).html($(e.target).data('store').TITLE);
    $('[data-store-address]', modal.$modal).html(abbr_store);
    modal.open();
    $('form', modal.$modal).validator();
  });

  $('.order-line').trigger('recalculate');
});