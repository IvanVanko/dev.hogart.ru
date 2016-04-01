$(document).ready(function(){
	$('.icon-email').click(function(){
		$.post('/ajax/send_to_email.php', {actionID: $('input[name="actionID"]').val(), email: $('input[name="user_mail"]').val()}, function(data){
			alert('Письмо успешно отправлено на Ваш E-mail!');
		});
		return false;
	});
});