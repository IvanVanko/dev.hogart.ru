<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/* 
Параметры:

SECTION_ID 		int  						ID секции
SECTION_CODE 	str  						Код секции
SELECT 			array 						Какие поля и свойства получить
ORDER 			array 						Сортировка
FILTER 			array 						Фильтр
CNT 			bool('Y','N')				Получать количество элементов в разделе
SET_TITLE 		bool('Y','N')				Уставаливать ли название элемента в title
ITEM_TEMPLATE 	str 						Шаблон для вывода элемента. Пример: '<a href="^DETAIL_PAGE_URL^">^NAME^</a>'

*/

$APPLICATION->IncludeComponent("kontora:section.detail", "", array(
	'SECTION_ID'    => '101',
	'ITEM_TEMPLATE' => '<a href="^SECTION_PAGE_URL^">^NAME^</a>',
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>