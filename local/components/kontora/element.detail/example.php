<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/* 
Параметры:

ID 				int  		обязательный	ID элемента
SELECT 			array 						Какие поля и свойства получить
PROPS 			bool('Y','N')				Получить все свойства элемента
SET_TITLE 		bool('Y','N')				Уставаливать ли название элемента в title
ITEM_TEMPLATE 	str 						Шаблон для вывода элемента. Пример: '<a href="^DETAIL_PAGE_URL^">^NAME^</a>'

*/

$APPLICATION->IncludeComponent("kontora:element.detail", "", array(
	'ID'            => '194',
	'ITEM_TEMPLATE' => '<a href="^DETAIL_PAGE_URL^">^NAME^</a><img src="^PREVIEW_PICTURE^" />',
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>