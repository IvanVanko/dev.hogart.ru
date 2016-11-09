/**
 * Created by gillbeits on 28/09/2016.
 */
$(function () {

  var filterForm = $('form[name="filter"]');
  filterForm.on('submit', function (e) {
    e.preventDefault();
    var params = $(this).serialize();
    var a = document.createElement('a');
    a.href = $(this).attr('action');
    a.search = "?" + $(this).serialize();
    Hogart_Lk.__proto__.insertToNode(a.href, 'orders-list');
  });

  $('[type="reset"]', filterForm).on('click', function (e) {
    e.preventDefault();
    filterForm.trigger('reset');
    filterForm.trigger('submit');
  });

  $('[name="date_from"], [name="date_to"]', filterForm).datetimepicker({
    locale: 'ru',
    format: 'L',
    useCurrent: false,
    useStrict: true
  });

});