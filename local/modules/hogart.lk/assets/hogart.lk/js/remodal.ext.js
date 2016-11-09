/**
 * Created by gillbeits on 01/09/16.
 */
function openConfirmationDialog(id, el) {
  var inst = $('[data-remodal-id="' + id + '"]').remodal();
  $('[data-remodal-id="' + id + '"] div[data-confirmation-wrapper]')
    .empty()
    .append('<h3>' + $(el).data('confirmation-title') + '</h3>')
    .append('<p>' + $(el).data('confirmation-message') + '</p>')
  ;
  inst.open();
  $(document)
    .off('confirmation', '[data-remodal-id="' + id + '"]')
    .on('confirmation', '[data-remodal-id="' + id + '"]', function() {
      eval('(function () { ' + $(el).data('confirmation-function') + ' })()');
      inst.close();
    });
}

function openAjaxLinkDialog (id, el) {
  var dialogSelector = '[data-remodal-id="' + id + '"]';
  var inst = $(dialogSelector).remodal();

  try {
    $(el).data('dialogEvents', eval('(function () { return ' + $(el).data('dialogEvents') + '})()'));
  } catch(e){}

  var fn = new Function($(el).data('link'));

  $(inst.$modal).off('submit', 'form');

  $('form', inst.$modal).validator().on('submit', function (e) {
    if (!e.isDefaultPrevented()) {
      fn.call(this);
      inst.close();
    }
    e.preventDefault();
    return true;
  });

  $(document)
    .off('confirmation', dialogSelector)
    .on('confirmation', dialogSelector, function() {
      if ($('form', this).length) {
        $('form', this).submit();
        return true;
      }
      fn.call(this);
      inst.close();
    });

  $.each($(el).data('dialogEvents') || {}, function (event, callback) {
    $(document)
      .off(event, dialogSelector)
      .on(event, dialogSelector, window[callback]);
  });
  inst.open();
  $(dialogSelector).trigger('hogart.lk.openajaxlinkdialog', [inst, el]);
}

function openEditDialog(id, el) {
  var inst = $('[data-remodal-id="' + id + '"]').remodal();

  try {
    eval('var edit_data = ' + $(el).data('edit'));

    $(inst.$modal).data('edit_data', edit_data || {});

    $('[data-remodal-id="' + id + '"] form input').each(function (i, input) {
      var value;
      if ($(input).data('bind')) {
        try {
          value = eval('(function () { return edit_data.' + $(input).data('bind') + '})()');
        } catch (ee) {}
      } else {
        value = edit_data[$(input).attr("name")] ? edit_data[$(input).attr("name")] : $(input).val();
      }
      $(input).val(value);
    });
    $(el).data('dialogEvents', eval('(function () { return ' + $(el).data('dialogEvents') + '})()'));
  } catch (e) {
  }

  $('[data-remodal-id="' + id + '"] form').on('submit', function (e) {
    if (!e.isDefaultPrevented()) {
      inst.close();
    }
  });

  $(document)
    .off('opening', '[data-remodal-id="' + id + '"]')
    .on('opening', '[data-remodal-id="' + id + '"]', function() {
      $('[data-remodal-id="' + id + '"] form').validator();
    });
  $(document)
    .off('confirmation', '[data-remodal-id="' + id + '"]')
    .on('confirmation', '[data-remodal-id="' + id + '"]', function() {
      $('[data-remodal-id="' + id + '"] form').submit();
    });

  $.each($(el).data('dialogEvents') || {}, function (event, callback) {
    $(document).on(event, '[data-remodal-id="' + id + '"]', window[callback]);
  });
  inst.open();
}

$(function () {
  $('a[data-confirmation]').on('click', function (e) {
    e.preventDefault();
    var id = $(e.target).data('confirmation');
    var modal = $('[data-remodal-id="' + id + '"]');
    modal.off('confirmation').on('confirmation', function () {
      document.location = $(e.target).attr('href');
    });
    modal.remodal().open();
  });
});