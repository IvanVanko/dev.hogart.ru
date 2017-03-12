/**
 * Created by gillbeits on 01/11/2016.
 */

var DataTableOptions = {
  info: false,
  searching: false,
  paging: false,
  responsive: true,
  fixedHeader: true
};

$(function () {
  var promises = [];

  $('[data-table]').each(function (i, t) {
    var dfd = $.Deferred();

    promises.push(dfd);

    $(t).on( 'draw.dt', function () {
      dfd.resolve();
    } );

    $(t).DataTable(DataTableOptions);
  });

  $.when.apply($, promises).done(function () {

    $('[data-relation-history]').each(function (i, el) {
      var relationId = $(el).attr('data-relation-history');
      if (!$(el).attr('data-relation-history')) return true;

      $('#svg').relationHistory('#' + relationId, '#' + $(el).attr('id'), {
        strokeColor: $(el).attr('data-relation-history-color')
      })
    });

  });

});