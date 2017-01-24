/**
 * Created by gillbeits on 17/09/16.
 */

(function ($) {
  "use strict";
  $.fn.changeApply = function (options) {
    this.each(function () {
      var self = $(this);
      var initData = {
        discard: $.type(self.data('changeDiscard')) !== "undefined" ? self.data('changeDiscard') : true,
        onBlurTrigger: $.type(self.data('changeOnBlurTrigger')) !== "undefined" ? self.data('changeOnBlurTrigger') : 'changeapply'
      };

      var settings = $.extend({
        applyTemplate: '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>',
        discardTemplate: '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>',
        onDiscard: $.noop,
        onBlurTrigger: 'changeapply'
      }, initData, options);

      if (self.is(':input') && ((['text', 'number']).indexOf(self.attr('type')) != -1 || self.prop("tagName") == "TEXTAREA")) {
        var id = "applier_" + Math.random().toString().substr(2);
        var container = $('<div class="input-change-apply" id="' + id + '"></div>');
        self.after(container);
        container.append(self.detach());

        var apply = $(settings.applyTemplate);
        apply.attr('data-btn-apply', true);
        container.append(apply);

        if (settings.discard) {
          var discard = $(settings.discardTemplate);
          discard.attr('data-btn-discard', true);

          var initRightPosition = apply.css('right');
          discard.css('right', initRightPosition);
          container.append(discard);
          apply.css('right', (discard.width() + parseInt(initRightPosition) + 5) + "px");
          discard.on('click', function () {
            self.trigger('changediscard');
          });
        }

        self.css('paddingRight', parseInt(apply.css('right')) * 2 + apply.width() + "px");

        var fn = new Function(self.attr('onchangeapply'));
        self.on('changeapply', fn);
        apply.on('click', function () {
          self.trigger('changeapply');
          self.data("changeApply", $.extend(self.data("changeApply"), {
            initVal: self.val()
          }));
          self.trigger('keyup');
        });

        self.on('changediscard', function () {
          self.val(self.data('changeApply').initVal);
          self.trigger('keyup');
          self.blur();
        });

        self.data("changeApply", {
          initVal: self.val(),
          apply: apply || null,
          discard: discard || null,
          changed: false
        });

        self.on('blur', function (e) {
          var data = self.data('changeApply');
          if (data.changed)
            self.trigger(settings.onBlurTrigger);
          return true;
        });

        self.on('keyup input', function (e) {
          var data = self.data('changeApply');
          if (e.keyCode == 13) {
            if (data.changed)
              self.trigger('changeapply');
            return true;
          }
          if (e.keyCode == 27) {
            self.trigger('changediscard');
          }
          if (e.target.value != data.initVal) {
            apply.css('visibility', 'visible');
            data.changed = true;
          } else if (apply.is(':visible')) {
            apply.css('visibility', 'hidden');
            data.changed = false;
          }
          if (!!data.discard && e.target.value != data.initVal) {
            discard.css('visibility', 'visible');
          } else if (!!data.discard && discard.is(':visible')) {
            discard.css('visibility', 'hidden');
          }
        });
      }
    });
  };
  $(function () { $('[data-change-apply]').changeApply(); });
})(jQuery);
