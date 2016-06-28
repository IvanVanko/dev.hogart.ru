/**
 * Created by gillbeits on 22/06/16.
 */
$(function () {
  $('>.caption', '[data-brand-wrapper], .perechen-produts.grid').each(function (i, caption) {
    caption = $(caption);
    var height = caption.parent().outerHeight();
    caption.parent().css({ 'max-height': height });
    caption.click( function (event) {
      if (event.target.tagName !== "A") {
        $(this).parent().toggleClass('closed');
      }
    });
  });
});