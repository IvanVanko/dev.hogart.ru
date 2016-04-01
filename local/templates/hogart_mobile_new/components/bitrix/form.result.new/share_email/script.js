$(document).ready(function () {
	if($('#popup-os #form_note').size() > 0){
		$('.js-popup-open[data-popup="#popup-os"]').click();
	}
	if($('#popup-pm #form_note').size() > 0){
		$('.js-popup-open[data-popup="#popup-pm"]').click();
	}
    if ($('.field.custom_label.date-picker').length){
        $('.field.custom_label.date-picker').find('img').css('opacity', 0);
        //$('.field.custom_label.date-picker').find('(DD.MM.YYYY)');
        $('.field.custom_label.date-picker input').click(function () {
            $('.field.custom_label.date-picker').find('img').click();
        });
        //console.log($('.field.custom_label.date-picker').text());
    }
});