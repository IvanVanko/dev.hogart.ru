<?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/* 
Параметры:

IBLOCK_ID 		int  		обязательный	ID Инфоблока
GROUP_BY  		array 						Группировка
SELECT 			array 						Какие поля и свойства получить
ORDER 			array 						Сортировка
FILTER 			array 						Фильтр
ELEMENT_COUNT 	int 						Количество элементов на странице
NAV 			bool('Y','N')				Выводить пагинацию
PROPS 			bool('Y','N')				Получить все свойства элемента
HTML_TYPE 		array 						Теги для вывода списка элементов. Пример: array('ul', 'li', '/li', '/ul')
ITEM_TEMPLATE 	str 						Шаблон для вывода элемента. Пример: '<a href="^DETAIL_PAGE_URL^">^NAME^</a>'

По заданию параметров GROUP_BY, SELECT, ORDER и FILTER подробно можно прочитать тут 
https://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getlist.php

В $result получаем массив с IDшниками выведенных компонентом элементов, которые могут пригодиться для исключения их при выводе в следующем компоненте.

Если необходимо получить все дополнительные свойства элементов, то нужно указать праметр 'PROPS' => 'Y'. 
Все свойста запишутся в массив $arResult['ITEMS']['номер_элемента']['PROPERTIES']/

Если используется параметр 'ITEM_TEMPLATE' то в 'SELECT' необходимо добавить все поля, которые использвутюся в 'ITEM_TEMPLATE (пример ниже)'.
То есть мы получим только указанные в SELECT поля. 
Для получения свойства 'SELECT' => array('PROPERTY_код_свойства' или, если свойство является списком - 'PROPERTY_код_свойства_VALUE').
Для вывода в ITEM_TEMPLATE - ^PROPERTY_КОД_СВОЙСТВА_VALUE^

*/

$result = $APPLICATION->IncludeComponent("kontora:element.list", "", array(
	'IBLOCK_ID'     => '15',
	'HTML_TYPE'     => array('ol', 'li', '/li', '/ol'),
	'ELEMENT_COUNT' => '5',
	'ITEM_TEMPLATE' => '<a href="^DETAIL_PAGE_URL^">^NAME^</a>^PROPERTY_TIME_VIDEO_VALUE^',
	'SELECT' 		=> array('NAME', 'DETAIL_PAGE_URL', 'PROPERTY_time_video'),
));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>