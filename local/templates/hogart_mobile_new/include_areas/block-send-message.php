<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<section class="slide hide" id="message_slide">
	<div class="top_block">
		 <?$APPLICATION->IncludeComponent(
			"bitrix:form.result.new",
			"feedback-mobile",
			Array(
				"WEB_FORM_ID"            => "1",
				"IGNORE_CUSTOM_TEMPLATE" => "N",
				"USE_EXTENDED_ERRORS"    => "Y",
				"SEF_MODE"               => "N",
				"VARIABLE_ALIASES"       => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),
				"CACHE_TYPE"             => "N",
				"CACHE_TIME"             => "3600",
				"LIST_URL"               => "",
				"EDIT_URL"               => "",
				"SUCCESS_URL"            => "",
				"SUCCESS_MESSAGE" => "Ваше сообщение успешно отправлено",
				"CHAIN_ITEM_TEXT"        => "",
				"CHAIN_ITEM_LINK"        => "",
				"AJAX_MODE" => "Y",  // режим AJAX
				"AJAX_OPTION_SHADOW" => "Y", // затемнять область
				"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
				"AJAX_OPTION_STYLE" => "N", // подключать стили
				"AJAX_OPTION_HISTORY" => "N",				
			), $component
		);?>
	</div>
</section>