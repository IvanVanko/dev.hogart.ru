$(document).ready(function(){
	/*$('.icon-email').click(function(){
		$.post('/ajax/send_to_email.php', {
            contactID: $('input[name="contactID"]').val(),
            email: $('input[name="user_mail"]').val()
        },
            function(data){
                console.log(data);
			alert('Письмо успешно отправлено на Ваш E-mail!');
		});
		return false;
	});*/

    $('.video-way').click(function () {
        var videoFile = $(this).attr('data-video');
        console.log(videoFile);
        if(videoFile != undefined){
            $(this).toggleClass('active');
            $('.way-scheme').slideUp(400);
            $('.way-scheme').siblings('a').removeClass('active');
            $(this).siblings('.video-way-file').slideToggle(400);
            $(this).siblings('.video-way-file iframe').remove();
            $(this).siblings('.video-way-file').html('<iframe width="100%" height="400px" src="https://www.youtube.com/embed/'+videoFile+
            '?rel=0" frameborder="0" allowfullscreen></iframe>');
        } else{
            $('.video-way-file').slideUp(400);
            $('.video-way-file iframe').remove();
            $(this).toggleClass('active');
            $(this).siblings('.way-scheme').slideToggle(400);
        }
        return false;
    });

});