/**
 * Created by gillbeits on 22/06/16.
 */
$(function () {
  $('>.caption', '[data-brand-wrapper], .perechen-produts.grid').each(function (i, caption) {
    caption = $(caption);
    var height = caption.parent().outerHeight();
    caption.parent().css({ 'max-height': height });
    caption.click( function (event) {
      var parent = $(this).parent();
      if (event.target.tagName !== "A") {
        if (!parent.is('.closed')) {
          parent.css('overflow', 'hidden');
        }
        parent.toggleClass('closed');

        if (!parent.is('.closed')) {
          // setTimeout(function () {
            parent.css('overflow', 'visible');
          // }, 1000);
        }

      }
    });
  });
});