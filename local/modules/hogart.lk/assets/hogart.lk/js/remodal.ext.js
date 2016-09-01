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

function openEditDialog(id, el) {
  var inst = $('[data-remodal-id="' + id + '"]').remodal();

  try {
    eval('var edit_data = ' + $(el).data('edit'));
    $('[data-remodal-id="' + id + '"] form input').each(function (i, input) {
      var value = "";
      $(input).val(value);
      if ($(input).data('bind')) {
        try {
          value = eval('(function () { return edit_data.' + $(input).data('bind') + '})()');
        } catch (ee) {}
      } else {
        value = edit_data[$(input).attr("name")];
      }
      $(input).val(value);
    });
  } catch (e) {
  }
  inst.open();
  $(document)
    .off('opening', '[data-remodal-id="' + id + '"]')
    .on('opening', '[data-remodal-id="' + id + '"]', function() {
      $('[data-remodal-id="' + id + '"] form').validator();
    });
  $(document)
    .off('confirmation', '[data-remodal-id="' + id + '"]')
    .on('confirmation', '[data-remodal-id="' + id + '"]', function() {
      $('[data-remodal-id="' + id + '"] form').submit();
      inst.close();
    });
}
