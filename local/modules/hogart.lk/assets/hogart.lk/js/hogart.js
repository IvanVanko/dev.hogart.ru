/**
 * Created by gillbeits on 06/09/16.
 */

window.Hogart_Lk = new (function ($) { this.$ = $; })(jQuery);

Hogart_Lk.__proto__.stickMenu = function (node) {
  var stick = $('[data-stick-block]', node);
  var parent = stick.parents('[data-stick-parent]');
  stick.stick_in_parent({
    offset_top: $('#header-block').outerHeight() + 20,
    parent: parent
  });
  return node;
};

Hogart_Lk.__proto__.makeLoader = function (node) {
  var id = "loader_" + Math.random().toString().substr(2);
  var loader = $('<div class="ajax-loader-wrapper"><div id="' + id + '" data-ajax-loader>' +
    '<div class="blob blob-0"></div>' +
    '<div class="blob blob-1"></div>' +
    '<div class="blob blob-2"></div>' +
    '<div class="blob blob-3"></div>' +
    '<div class="blob blob-4"></div>' +
    '<div class="blob blob-5"></div>' +
    '</div></div>');
  if ($(node).data('loader-wrapper')) {
    node = $(node).data('loader-wrapper');
  }
  $(node).addClass('ajax-loading').append(loader);

  return loader;
};

Hogart_Lk.__proto__.insertToNode = function (url, node) {
  node = $("#" + node);
  if (node.length)
  {
    var loader = this.makeLoader(node);
    return BX.ajax.get(url, function(data) {
      var loader_parent = loader.parent();
      $(node).empty().append(data);
      $(node).trigger("hogart.lk.ajaxDataAppend", [node]);
      loader_parent.removeClass('ajax-loading');
      loader.remove();
    });
  }
};

Hogart_Lk.__proto__.getAjaxUrl = function (element, ajaxid, url) {
  var a = document.createElement('a');
  a.href = url;
  $(element).trigger('hogart.lk.ajaxurlchange', [a]);
  return a.href;
};

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

Hogart_Lk.__proto__.createAjaxObserver = function (selector, callback, options) {
  options = $.extend( { childList: true, characterData: true, attributes: true, subtree: true }, options );
  var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;
  var obServer = new MutationObserver(callback);

  $(function(){
    $(selector).each ( function () {
      obServer.observe (this, options);
    });
  });
  return obServer;
};

$(function () {
  $(document).on('hogart.lk.ajaxurlchange', '[data-onchangeurl]', function (e, a) {
    e.stopPropagation();
    var _ = {};
    (new Function('_', $(e.target).data('onchangeurl'))).call(this, _);
    for (var i in _) {
      _[i] = _[i].call(this, this);
      if ($.type(_[i]) == 'object' || $.type(_[i]) == 'array') {
        $.each(_[i], function (k, v) {
          a.search += "&" + i + "[" + k + "]=" + v;
        });
      } else {
        a.search += "&" + i + "=" + _[i];
      }
    }
  });

  if (typeof $.notifyDefaults == "function") {
    $.notifyDefaults({
      newest_on_top: true,
      offset: {
        y: 105,
        x: 10
      },
      animate: {
        enter: 'animated zoomInRight',
        exit: 'animated zoomOutUp'
      }
    });
  }

});