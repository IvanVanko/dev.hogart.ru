/**
 * Created by gillbeits on 16/05/2017.
 */
/**
 * Created by gillbeits on 16/05/2017.
 */

function toggleBrandFilter(input) {
  var $input = $(input);
  var brandInputs = $input.parents('.bx-filter-parameters-box-container').find('input[data-code]:checked');

  if (brandInputs.length) {
    $('.brand[data-code]').addClass('hidden');
    brandInputs.each(function (i, el) {
      $('.brand[data-code="' + $(el).data('code') + '"]').removeClass('hidden');
    });
  } else {
    $('.brand[data-code]').removeClass('hidden');
  }

  $('.depth-3')
    .addClass('hidden')
    .find('.brand[data-code]:not(.hidden)')
    .each(function (i, el) {
      $(el).parents('.depth-3').removeClass('hidden')
    });

  $('.d-3')
    .addClass('hidden')
    .each(function (i, el) {
      if ($('.depth-3:not(.hidden)', el).length) {
        $(el).removeClass('hidden');
      }
    });

  $('.d-2')
    .addClass('hidden')
    .each(function (i, el) {
      if ($('.d-3[data-parent="' + $(el).data('id') + '"]:not(.hidden)').length) {
        $(el).removeClass('hidden');
      }
    });

  $('.d-1')
    .addClass('hidden')
    .each(function (i, el) {
      if ($('.d-2[data-parent="' + $(el).data('id') + '"]:not(.hidden)').length) {
        $(el).removeClass('hidden');
      }
    });
}