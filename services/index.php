<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Сервисная служба Хогарт");
?>

        <?/*        <h1><?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                    "AREA_FILE_SHOW" => "page",
                    "AREA_FILE_SUFFIX" => "inc_title",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                )
            );?></h1>
	    <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
			    "AREA_FILE_SHOW" => "page",
			    "AREA_FILE_SUFFIX" => "inc_top",
			    "AREA_FILE_RECURSIVE" => "Y",
			    "EDIT_TEMPLATE" => "standard.php"
		    )
	    );?>

        <img alt="" class="img-center" src="/images/reg_video.jpg">
*/?>
        <?
        $APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"service-list", 
	array(
		"IBLOCK_ID" => "20",
		"IBLOCK_TYPE" => "company",
		"SORT" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "fotos",
			2 => "",
		),
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "0",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);
        ?>
        <!--<br/>
        <hr/>
        <br/>
        <div class="service-item">

            <h3 class="services-h3">Профилактическое сервисное обслуживание техники</h3>
            <ul>
                <li>Комплексная проверка систем</li>
                <li>Тестирование устройств автоматики</li>
                <li>Диагностика</li>
                <li>Балансировка</li>
                <li> Устранение неполадок и своевременная замена элементов, ресурс работы которых близок к завершению</li>
                <li>Установка СМС сигнализации на котельное оборудование, срабатываемое и оповещающее при утечке газа либо при отклонении от заданных параметров работы котла.</li>
            </ul>
            <p>Все это повышает эксплуатационные характеристики и обеспечивает бесперебойную работу техники в любых климатических условиях.</p>
        </div>
    <div class="service-item">
        <h3 class="services-h3">Гарантийное и сервисное обслуживание оборудования</h3>
        <ul>
        	<li> Котельное оборудование:</li>
        	<li>De Dietrich (Франция), Viessmann (Германия), Wolf (Германия)</li>
        	<li>Горелочное оборудование:</li>
        	<li>Giersch (Германия), Weishaupt ( Германия)</li>
        	<li>Установки поддержания давления, бойлеры Reflex (Германия)</li>
        	<li>Пароувлажнители Nordmann (Швейцария).</li>
        	<li>Регулирующая арматура для гидравлической увязки Oventrop(Германия)</li>
        	<li>Вентиляционное оборудование Wolf (Германия)</li>
        </ul>
    </div>
    <div class="service-item">
        <h3 class="services-h3">Монтаж</h3>
        <ul>
        	<li>Системы кондиционирования;</li>
        	<li>Системы вентиляции;</li>
        	<li>Мульти системы (VRV,VRF);</li>
        	<li> Чиллеры (фанкойлы);</li>
        	<li>Пуск и наладка;</li>
        	<li>Автоматика управления приточно-вытяжной системой вентиляции;</li>
        	<li>Автоматика управления системой кондиционирования;</li>
        	<li>Холодильных контуров;</li>
        	<li>Парогенераторов (Nordmann);</li>
        	<li>Сервисное обслуживание;</li>
        	<li>Системы кондиционирования;</li>
        	<li>Системы вентиляции;</li>
        	<li>Парогенераторы;</li>
        	<li>Сантехническое оборудование.</li>
        </ul>
    </div>-->
        <br /><br />
	    <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
			    "AREA_FILE_SHOW" => "page",
			    "AREA_FILE_SUFFIX" => "inc_bottom",
			    "AREA_FILE_RECURSIVE" => "Y",
			    "EDIT_TEMPLATE" => "standard.php"
		    )
	    );?>

    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="padding">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:form.result.new", "server_service",
                    array(
                        "WEB_FORM_ID" => "8",
                        "IGNORE_CUSTOM_TEMPLATE" => "N",
                        "USE_EXTENDED_ERRORS" => "N",
                        "SEF_MODE" => "N",
                        "VARIABLE_ALIASES" => Array("WEB_FORM_ID"=>"WEB_FORM_ID","RESULT_ID"=>"RESULT_ID"),
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600",
                        "LIST_URL" => "",
                        "EDIT_URL" => "",
                        "SUCCESS_URL" => "",
                        "CHAIN_ITEM_TEXT" => "",
                        "CHAIN_ITEM_LINK" => "",

                        "SUCCESS_MESSAGE" => "Спасибо за обращение в нашу компанию. В ближайшее время наш специалист свяжется с вами для уточнения деталей."
                ), $component);?>
            </div>
        </div>
    </aside>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>