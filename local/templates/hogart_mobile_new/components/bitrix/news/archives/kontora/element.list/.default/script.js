$(document).ready(function(){
	$('.action_filter input').change(function(){
		$('.action_filter').submit();
	});
	$('.action_filter select').change(function(){
		$('.action_filter').submit();
	});
});