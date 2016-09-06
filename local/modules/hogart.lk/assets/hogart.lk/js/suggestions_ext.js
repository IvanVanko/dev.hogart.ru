/**
 * Created by gillbeits on 05/09/16.
 */

$(function () {
  suggest(document);
});

function suggest (context) {
  $('[data-suggest]', context).each(function (i, el) {
    $(el).suggestions({
      serviceUrl: window.DaData.serviceUrl,
      token: window.DaData.token,
      type: ("" + $(el).data('suggest')).toUpperCase(),
      onSelect: function (suggestion) {
        $('input[name="__' + $(this).attr('name') + '"]').val(JSON.stringify(suggestion));
      }
    });
    $(el).after('<input type="hidden" name="__' + $(el).attr('name') + '">');
    var params = $(el).data('suggest-params');
    if (params) {
      $(el).data("suggestions").setOptions({
        params: params
      })
    }
    var onSelect = $(el).data('suggest-onselect');
    if (onSelect && window[onSelect] && typeof window[onSelect] === "function") {
      $(el).on('suggestions-select', window[onSelect]);
    }
  });
}

(function ($) {
  window.DaData = {};

  window.DaData.MutationObserver    = window.MutationObserver || window.WebKitMutationObserver;
  window.DaData.suggestObserverHandler = function (mutationRecords) {
    $.each(mutationRecords, function (i, obs) {
      $.each(obs.addedNodes, function (j, el) {
        suggest($(el));
      });
    });
  };
  window.DaData.myObserver          = new MutationObserver (window.DaData.suggestObserverHandler);
  window.DaData.obsConfig           = { childList: true, characterData: true, attributes: true, subtree: true };

  window.DaData.addToObserver = function (selector) {
    $(selector).each ( function () {
      window.DaData.myObserver.observe (this, window.DaData.obsConfig);
    });
  };

  /**
   * Инициализирует подсказки по ФИО на указанном элементе
   * @param $surname jQuery-элемент для текстового поля с фамилией
   * @param $name jQuery-элемент для текстового поля с именем
   * @param $patronymic jQuery-элемент для текстового поля с отчеством
   * @constructor
   */
  window.DaData.init_fio = function ($surname, $name, $patronymic) {
    var self = {};
    self.$surname = $surname;
    self.$name = $name;
    self.$patronymic = $patronymic;
    var fioParts = ["SURNAME", "NAME", "PATRONYMIC"];
    // инициализируем подсказки на всех трех текстовых полях
    // (фамилия, имя, отчество)
    $.each([$surname, $name, $patronymic], function(index, $el) {
      var sgt = $el.suggestions({
        serviceUrl: window.DaData.serviceUrl,
        token: window.DaData.token,
        type: "NAME",
        triggerSelectOnSpace: false,
        hint: "",
        noCache: true,
        params: {
          // каждому полю --- соответствующая подсказка
          parts: [fioParts[index]]
        },
        onSearchStart: function(params) {
          // если пол известен на основании других полей,
          // используем его
          var $el = $(this);
          params.gender = isGenderKnown.call(self, $el) ? self.gender : "UNKNOWN";
        },
        onSelect: function(suggestion) {
          // определяем пол по выбранной подсказке
          self.gender = suggestion.data.gender;
        }
      });
    });
  };

  /**
   * Проверяет, известен ли пол на данный момент
   * @param $el элемент, в котором находится фокус курсора
   * @returns {boolean}
   */
  function isGenderKnown($el) {
    var self = this;
    var surname = self.$surname.val(),
      name = self.$name.val(),
      patronymic = self.$patronymic.val();
    if (($el.attr('id') == self.$surname.attr('id') && !name && !patronymic) ||
      ($el.attr('id') == self.$name.attr('id') && !surname && !patronymic) ||
      ($el.attr('id') == self.$patronymic.attr('id') && !surname && !name)) {
      return false;
    } else {
      return true;
    }
  }
})(jQuery);