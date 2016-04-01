$(document).ready(function () {
	$('.video-way').click(function () {
        var videoFile = $(this).attr('data-video');
        console.log(videoFile);
        $(this).siblings('.video-way-file').slideDown(400);
        return false;
    });
});