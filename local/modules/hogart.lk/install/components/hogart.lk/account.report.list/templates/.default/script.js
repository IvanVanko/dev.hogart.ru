/**
 * Created by gillbeits on 17/01/2017.
 */

$(function () {
  var filterForm = $('form[name="filter"]');
  $('[name="date_from"], [name="date_to"]', filterForm).datetimepicker({
    locale: 'ru',
    format: 'L',
    useCurrent: false,
    useStrict: true
  });

  filterForm.on('submit', function (e) {
    e.preventDefault();
    var params = $(this).serialize();
    var a = document.createElement('a');
    a.href = $(this).attr('action');
    a.search = "?" + $(this).serialize();
    Hogart_Lk.__proto__.insertToNode(a.href, 'reports-list');
  });

  $('[type="reset"]', filterForm).on('click', function (e) {
    e.preventDefault();
    filterForm.trigger('reset');
    filterForm.trigger('submit');
  });

  $('#category').treeMultiselect({ sortable: true, hideSidePanel: true, startCollapsed: true });
  $('#report').on('change', function () {
    $('#companies').toggle($(this).val() != 'stock');
  }).change();

  Sortable.create(document.getElementById('available-group'), {
    group: "groups",
    animation: 150
  });

  var groupList = Sortable.create(document.getElementById('enabled-group'), {
    group: "groups",
    animation: 150,
    onSort: function (event) {
      $('input[name="groups"]').val(groupList.toArray().join(','))
    }
  });
});