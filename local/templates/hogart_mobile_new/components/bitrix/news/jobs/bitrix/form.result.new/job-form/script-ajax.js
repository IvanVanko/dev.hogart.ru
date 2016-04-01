$(document).ready(function () {
	console.log('+');

	// Отправка AJAX запроса
	$('.js-validation-form-new form').submit(function (e) {      // Обработка отправки данных формы
		e.preventDefault();                  // Сброс стандартного обработчика формы
		formData = $(this).serialize() + "&web_form_submit=Отправить";   // Сохраняем массив введенных данных включая значение кнопки "Отправить", без этого компонент Битрикса не примет данные


		$.post(      // Отправим POST запрос серверу
			$(this).attr('action') + '?AJAX_REQUEST=Y',   // Текущая страница с дописанным параметром - по нему подключается пустой шаблон с одним #WORK_AREA#
			formData,
			function (response) {
				var message = $(".js-validation-form-new-answer").html(response);         // Сохраняем загруженные данные на странице в невидимом блоке
				console.log(formData);
				if (message.find(".js-validation-form-new-answer").length) {                        // Если в этих данных есть элементы для показа (ошибка или сообщение)
					//var answer_html = $(message.find(".js-validation-form-new-answer")).html();
					$('.container.main-container').prepend('<div class="f-message"></div>');
					setTimeout(function () {
						$('.f-message').html(toString(message));
					},500);

					//captcha_update();         // Сбросим капчу в форме
				}

				if (message.find(".form-note").length) {   // Если данные формы приняты сбросим все поля
					$('.form-ask input').val(" ");
					$('.form-ask textarea').val(" ");
				}
			}
		);
		return false;
	});
});