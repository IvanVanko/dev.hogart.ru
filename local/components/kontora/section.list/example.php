<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/* 
Параметры:

IBLOCK_ID 		int  		обязательный	ID Инфоблока
SELECT 			array 						Какие поля и свойства получить
ORDER 			array 						Сортировка
FILTER 			array 						Фильтр
ELEMENT_COUNT 	int 						Количество элементов на странице
NAV 			bool('Y','N')				Выводить пагинацию
CNT 			bool('Y','N')				Получать количество элементов в разделе
HTML_TYPE 		array 						Теги для вывода списка элементов. Пример: array('ul', 'li', '/li', '/ul')
ITEM_TEMPLATE 	str 						Шаблон для вывода элемента. Пример: '<a href="^DETAIL_PAGE_URL^">^NAME^</a>'
*/

$APPLICATION->IncludeComponent("kontora:section.list", "", array(
	'IBLOCK_ID'     => '9',
	'ITEM_TEMPLATE' => '<a href="^DETAIL_PAGE_URL^">^NAME^</a>',
	'HTML_TYPE'     => array('ol', 'li', '/li', '/ol'),
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>