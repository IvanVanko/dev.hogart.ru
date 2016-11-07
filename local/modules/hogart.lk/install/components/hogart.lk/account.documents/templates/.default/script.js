/**
 * Created by gillbeits on 05/09/16.
 */
$.fn.validator.Constructor.INPUT_SELECTOR = 'fieldset:not(:disabled) :input:not([type="hidden"], [type="submit"], [type="reset"], button)';

$(function () {
  setTimeout(function () {
    window.DaData.init_fio(
      $('fieldset[name="company_type_3"] input[name="last_name"]'),
      $('fieldset[name="company_type_3"] input[name="name"]'),
      $('fieldset[name="company_type_3"] input[name="middle_name"]')
    );
    window.DaData.init_fio(
      $('fieldset[name="company_type_1"] input[name="director_last_name"]'),
      $('fieldset[name="company_type_1"] input[name="director_name"]'),
      $('fieldset[name="company_type_1"] input[name="director_middle_name"]')
    );

    window.DaData.init_fio(
      $('form[name="add-contact"] input[name="last_name"]'),
      $('form[name="add-contact"] input[name="name"]'),
      $('form[name="add-contact"] input[name="middle_name"]')
    );
  }, 0);

  $(document).on('change', 'select[name="company_type"]', function () {
    var form = $(this).parents('form');
    if ($('input[name="action"]', form).val() != 'edit-company') {
      $('> fieldset :input', form).each(function() {
        $(this).val(this.defaultValue);
      });
    }
    form.find('fieldset').find($.fn.validator.Constructor.INPUT_SELECTOR).attr('data-validate', 'false');
    form.find('fieldset').attr('hidden', 'hidden').attr('disabled', 'disabled');
    form.find('fieldset[name="company_type_' + $(this).val() + '"]').find($.fn.validator.Constructor.INPUT_SELECTOR).attr('data-validate', 'true');
    form.find('fieldset[name="company_type_' + $(this).val() + '"]').removeAttr('hidden').removeAttr('disabled');
    form.validator('update');
  });
  $('select[name="company_type"]').change();

  $(document).on('change', 'select[name="doc_pass"]', function () {
    $('[data-doc-type]').hide();
    $('[data-doc-type="' + $(this).val() + '"]').show();
  });
  $('select[name="doc_pass"]').change();

  window.DaData.addToObserver('fieldset[name="company_type_1"]');
  window.DaData.addToObserver('fieldset[name="company_type_2"]');
  window.DaData.addToObserver('fieldset[name="company_type_3"]');

  $(document).on('change', '[name^="payment_account[is_main]"]', function (e) {
    if ($(this).is(':checked')) {
      var parent = $(this).parents('fieldset');
      var index = $('[name^="payment_account[is_main]"]', parent).index($(this));
      $('[name^="payment_account[is_main]"]', parent).each(function (_, el) {
        if (index != _) {
          $(el).removeAttr("checked");
        }
      });
    } else {
      e.preventDefault();
      $(this).prop('checked', 'checked');
      return false;
    }
  });

  // клонирование реквизитов счета
  $(document).on('click', '[data-payment-account] a[data-cloner]', function () {
    Hogart_Lk.clone('[data-payment-account]', '[data-payment-account]:last', this, function (clone) {
      $(':input', clone).each(function() {
        $(this).val("");
        $('[name="^__"][type="hidden"]', clone).remove();
      });
      $(':checked', clone).removeAttr("checked");
    });
  });

  // клонирование полей email
  $(document).on('click', '[data-contact-email] a[data-cloner]', function () {
    Hogart_Lk.clone('[data-contact-email]', '[data-contact-email]:last', this, function (clone) {
      $(':input', clone).each(function() {
        $(this).val(this.defaultValue);
      });
    });
  });
  // клонирование полей телефона
  $(document).on('click', '[data-contact-phone] a[data-cloner]', function () {
    Hogart_Lk.clone('[data-contact-phone]', '[data-contact-phone]:last', this, function (clone) {
      $(':input', clone).each(function() {
        $(this).val(this.defaultValue);
      });
    });
  });

  window.Hogart_Lk.createAjaxObserver('#companies-ajax', function (MutationRecords) {
    MutationRecords.forEach(function (record) {
      $('.selectpicker', record.target).selectpicker();
    });
  });

});

function partySelect (event, suggestion) {
  var fieldset = $(event.currentTarget).parents('fieldset');
  $('input[name="inn"]', fieldset).val(suggestion.data.inn);
  $('input[name="kpp"]', fieldset).val(suggestion.data.kpp);
  $('input[name="address[2]"]', fieldset).val(suggestion.data.address.value);
  $('input[name="__address[2]"]', fieldset).val(JSON.stringify(suggestion.data.address));

  var management = suggestion.data.management.name.split(' ');
  $('input[name="director_last_name"]', fieldset).val(management[0]);
  $('input[name="director_name"]', fieldset).val(management[1]);
  $('input[name="director_middle_name"]', fieldset).val(management[2]);
}

function partyIndividualSelect (event, suggestion) {
  var fieldset = $(event.currentTarget).parents('fieldset');
  $('input[name="inn"]', fieldset).val(suggestion.data.inn);
  $('input[name="registration_date"]', fieldset).val(moment(suggestion.data.state.registration_date, 'x').format('DD.MM.YYYY'));
}

function openingAddressEdit (event) {
  var suggest = $('[name="address"]:input:eq(0)', event.target).data('suggestions');
  suggest.proceedChangedValue();
  suggest.inputPhase.then(function () {
    suggest.selectCurrentValue();
  });
}

function openingCompanyEdit (event) {
  var object = $(event.target).data('edit_data');
  var form = $('form', event.target);
  var fieldset = $('fieldset[name="company_type_' + object.type + '"]', event.target);

  $('select[name="company_type"]', event.target).val(object.type).change().attr("disabled", true).attr('data-validate', 'false');
  $('[name="is_active"]', event.target).attr("disabled", true).hide();

  if (object.addresses) {
    $.each(object.addresses, (function (type, addressList) {
      addressList.forEach(function (address) {
        if (!address.value) {
          address.value = [
            address.postal_code,
            address.region,
            address.city,
            address.street,
            address.house,
            address.building,
            address.flat
          ].join(" ");

          if ($('[name="address[' + type + ']"]', fieldset).length) {
            $('[name="address[' + type + ']"]', fieldset).val(address.value);
          }
        }
      });
    }));
  }

  if (object.type == 1) {
    $('[name="name"], [name="inn"], [name="kpp"]', fieldset).attr("disabled", true).attr('data-validate', 'false');
  } else if (object.type == 2) {
    $('[name="name"], [name="inn"]', fieldset).attr("disabled", true).attr('data-validate', 'false');
  } else if (object.type == 3) {
    var name = object.name.split(' ');
    $('[name="last_name"]', fieldset).val(name[0]);
    $('[name="name"]', fieldset).val(name[1]);
    $('[name="middle_name"]', fieldset).val(name[2]);
    $('select[name="doc_pass"]', fieldset).val(object.doc_pass).change();
  }

  $('[data-suggest="address"], [data-suggest="bank"]', fieldset).each(function (i, el) {
    var suggest = $(el).data('suggestions');
    if (suggest) {
      try {
        suggest.options.autoSelectFirst = true;
        suggest.proceedChangedValue();
        suggest.inputPhase.then(function () {
          suggest.selectCurrentValue();
        });
      } catch (e) {}
    }
  });

  $('[data-mask]:not(:disabled)', fieldset).trigger('focus.bs.inputmask.data-api');
  form.validator('update');
}

