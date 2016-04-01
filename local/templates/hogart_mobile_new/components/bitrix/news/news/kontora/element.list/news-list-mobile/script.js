/* Подключение подгрузки новостей
 $(document).ready(function(){
    //путь к файлу с компонентом. Указываем параметр
    var path = "/include/news_list.php?ajax=Y";
    //счетчик страниц
    var currentPage = 1;
   var count = $(".news-block").data("count");

    $(".show-more").click(function(e){
        //делаем ajax запрос и сразу инкремент номера страницы
        $.get(path, {PAGEN_1: ++currentPage}, function(data){
            //добавим новости к списку
            $(".news-list").append(data);
        });

        //отключим скролл к верху документа
        e.preventDefault();
    });
});
*/

/*$(document).ready(function(){
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
});*/