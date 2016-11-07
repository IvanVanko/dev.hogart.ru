/**
 * Created by gillbeits on 01/11/2016.
 */

$(function () {
    $('[data-relation-history]').each(function (i, el) {
        var relationId = $(el).attr('data-relation-history');
        if (!$(el).attr('data-relation-history')) return true;

        $('#svg').relationHistory('#' + relationId, '#' + $(el).attr('id'), {
            strokeColor: $(el).attr('data-relation-history-color')
        })
    });
});