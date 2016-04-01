$(document).ready(function () {
	$('#addOneFoto').click(function () {
		$(this).before('<div class="field custom_upload white-btn">'
			+ '<input type="file" name="form_file_32[]" accept="application/pdf,application/msword,text/plain">'
			+ '<input type="hidden" name="form_file_32_old_id[]" value="" />'
			+ '<label>Прикрепить опросный лист</label>'
			+ '</div>'
		);
	});
});
