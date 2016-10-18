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
      data: 'measure',
      targets: 5,
      orderable: false
    },
    {
      render: $.fn.dataTable.render.number(' ', '.', 2, '', ''),
      targets: [6, 7, 8, 9]
    }
  ]
};

$.fn.validator.Constructor.INPUT_SELECTOR = 'fieldset:not(:disabled) :input:not([type="hidden"], [type="submit"], [type="reset"], button)'

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
    $('input[name="driver_last_name"]'),
    $('input[name="driver_name"]'),
    $('input[name="driver_middle_name"]')
  );
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
    daysOfWeekDisabled: [0, 5],
    minDate: moment().add(1, 'days')
  });

  $(document).on('change', '[data-switch][name="new_address"]', function (e) {
    $('fieldset[data-new-address]')
      .attr('disabled', 'disabled')
      .hide();

    $('fieldset[data-new-address="' + (this.checked ? 'true' : 'false') + '"]')
      .attr('disabled', null)
      .show();
  });

  $(document).on('change', '[data-switch][name="new_contact"]', function (e) {
    $('fieldset[data-new-contact]')
      .attr('disabled', 'disabled')
      .hide();

    $('fieldset[data-new-contact="' + (this.checked ? 'true' : 'false') + '"]')
      .attr('disabled', null)
      .show();
  });

  $(document).on('change', '[data-switch][name="is_tk"]', function (e) {
    $('fieldset[data-tk]')
      .attr('disabled', !this.checked ? "disabled" : null)
      .toggle(this.checked);
  });

  $('[data-switch][name="delivery_type"]').on('change', function (e) {
    $('[data-delivery-type]').removeAttr('disabled').removeClass('active');
    $('[data-delivery-type]:not([data-delivery-type="' +  $(this).val() + '"])').attr('disabled', 'disabled');
    $('[data-delivery-type="' + $(this).val() + '"]').addClass('active');
  });

  $('[data-table]').each(function (i, t) {
    $(t).DataTable(DataTableOptions);
  });

  Hogart_Lk.stickMenu($('[data-store]'));

  $('.title :input').on('change', function (e) {
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
  });

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
});