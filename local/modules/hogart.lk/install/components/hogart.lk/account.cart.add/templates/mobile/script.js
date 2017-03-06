/**
 * Created by gillbeits on 15/10/2016.
 */
$(function () {
  var cart_counter = $('.cart-counter2'), counter = $('.counter', cart_counter);
  Hogart_Lk.EventSource.addEventListener("message", function (event) {
    var type = event.type;
    if (type === "message") {
      var message;
      try {
        message = JSON.parse(event.data);
      } catch (exception) {
      }
      if ($.isPlainObject(message)) {
        switch (message.type) {
          case 'cart_counter':
            if (counter.text() != message.count) {
              counter.text(message.count);
              cart_counter
                .one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                  $(this).removeClass('animated tada');
                })
                .addClass('animated tada');
            }
            break;
        }
      }
    }
  });
});