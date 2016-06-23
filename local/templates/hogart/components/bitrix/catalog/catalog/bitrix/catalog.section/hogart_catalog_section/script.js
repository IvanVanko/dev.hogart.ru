/**
 * Created by gillbeits on 22/06/16.
 */
$(function () {
  $('>.caption', '[data-brand-wrapper]').each(function (i, caption) {
    caption = $(caption);
    var height = caption.parent().outerHeight();
    caption.parent().css({ 'max-height': height });
    caption.click( function () {
      $(this).parent().toggleClass('closed');
    });
  });
});