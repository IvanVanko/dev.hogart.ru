$(document).ready(function () {
	$('#addOneFoto').click(function () {
		$('.field.custom_upload.white-btn:visible').last().next().slideDown();
		return false;
	});
});
