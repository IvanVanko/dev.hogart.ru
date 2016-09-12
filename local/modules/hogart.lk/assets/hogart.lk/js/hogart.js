/**
 * Created by gillbeits on 06/09/16.
 */

window.Hogart_Lk = new (function ($) { this.$ = $; })(jQuery);

Hogart_Lk.__proto__.clone = function (selector, after, context, callback) {
  if (typeof context == "function") {
    callback = context;
    context = document;
  } else {
    context = $(context).parents(selector).parent();
  }
  $(selector + ':eq(0) :input', context).each(function (_, el) {
    if (!$(el).attr('default-name')) {
      $(el).attr('default-name', $(el).attr('name'));
      $(el).attr('name', $(el).attr('name') + '[0]');
    }
  });
  var clone = $(selector + ':eq(0)', context).clone(false, true);
  var index = $(selector, context).length;
  $(':input', clone).each(function (_, el) {
    $(el).attr('name', $(el).attr('default-name') + '[' + index +']');
  });
  $($(after, context)).after(clone);
  if ($.isFunction(callback)) {
    return callback(clone);
  }
};

Hogart_Lk.__proto__.createAjaxObserver = function (selector, callback) {
  var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
  var obServer = new MutationObserver(callback);

  $(selector).each ( function () {
    obServer.observe (this, { childList: true, characterData: true, attributes: true, subtree: true });
  });

  return obServer;
};