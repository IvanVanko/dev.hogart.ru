/**
 * Created by gillbeits on 29/09/2016.
 */
var DataTableOptions = {
  info: false,
  searching: false,
  paging: false,
  responsive: true,
  fixedHeader: true,
  columnDefs: [
    {
      render: $.fn.dataTable.render.number(' ', '.', 2, '', ''),
      targets: [6, 7, 8, 9]
    },
    {
      data: 'measure',
      targets: 5,
      orderable: false
    }
  ]
};

$(function () {
  Hogart_Lk.stickMenu("[data-stick-parent]");
  $('[data-table]').each(function (i, t) {
    $(t).DataTable(DataTableOptions);
  });
});