$(document).ready(function(){
	$('select[name="direction"]').change(function(){
		$.post('/ajax/news_filter.php', {directionID: $(this).val()}, function(data){
			$("select[name='catalog_section']").empty().append('<option value="">Выбрать тип товара</option>');
			jQuery.each(data, function(i, val) {
				$("select[name='catalog_section']").append('<option value="'+i+'">'+val+'</option>');
			});
			$("select[name='catalog_section']").change();

			console.log(data);
		}, "json");
	});
	$('.news_tags label').click(function () {
		var Nform = $(this);
		setTimeout(function () {
			Nform.parents('form').submit();
		}, 300);
	});
});