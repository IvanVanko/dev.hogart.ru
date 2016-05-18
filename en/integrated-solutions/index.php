<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Solutions");
?>
    <div class="inner no-full">
    <h1><? $APPLICATION->ShowTitle() ?></h1>

    <h2>— одно из основных направлений работы компании "Хогарт" на рынке поставок сантехнического, оборудования, систем&nbsp;отопления
        и вентиляции</h2>

    <p>
        "Хогарт" на протяжении многих лет накапливал опыт реализации комплексных поставок на разнообразные&nbsp;
        отраслевые объекты.
    </p>

    <p>
        Каждый тип строительного объекта имеет свои отличительные особенности. При комплектации отопительной,
        вентиляционной и сантехнической системы приходится решать специфические вопросы&nbsp;и пожелания&nbsp;для
        достижения запланированного результата.
    </p>

    <p>
        Здесь мы хотим поделиться знаниями,&nbsp;на что обращать внимание и как получить максимальный синергетический
        эффект, при проектировании инженерных систем в&nbsp;отдельно взятых строительных объектах &nbsp;- от коттеджей
        до торговых центров и заводов. <br>
    </p>
    </div>
    <br>
    <div class="inner no-full">
        <div class="icon-com">
            <h2>Ознакомьтесь с нашим передовым опытом поставок оборудования</h2>
            <? $APPLICATION->IncludeComponent(
                "kontora:section.list",
                "counter-com",
                Array(
                    "IBLOCK_ID" => 35,
                    "PROPS" => "Y",
                    "SEF_MODE" => "Y",
                    "SEF_FOLDER" => "/en/integrated-solutions/",
                    "VARIABLE_ALIASES" => Array(),
                    "VARIABLE_ALIASES" => Array()
                )
            ); ?>
        </div>
        <div class="icon-com">
            <? $APPLICATION->IncludeComponent(
                "kontora:section.list",
                "counter-zones",
                Array(
                    "IBLOCK_ID" => 36,
                    "PROPS" => "Y",
                    "SEF_MODE" => "Y",
                    "SEF_FOLDER" => "/en/integrated-solutions/",
                    "VARIABLE_ALIASES" => Array(),
                    "CNT" => "Y",
                    "CNT_ACTIVE" => "Y",
                    "VARIABLE_ALIASES" => Array()
                )
            ); ?>
        </div>
        <p class="complex-page-all-href">
            <a href="all_projects.php">All objects</a>
        </p>

        <div class="carusel">
            <div class="inner">
                <h2><a href="/integrated-solutions/all_projects.php">References</a></h2>
                <? $section_ids = BXHelper::getSections(array('ID' => 'ASC'), array('ACTIVE' => 'Y',
                                                                                    'IBLOCK_ID' => PROJECT_TYPE_IBLOCK_ID), false, array('ID'), true, 'ID'); ?>
                <? $section_ids = array_keys($section_ids['RESULT']);?>
                <? $APPLICATION->IncludeComponent(
                    "kontora:element.list",
                    "real-projects",
                    Array(
                        "IBLOCK_ID" => 37,
                        "PROPS" => "Y",
                        "FILTER" => array("PROPERTY_SOLUTION_ID" => $section_ids),
                        "SEF_MODE" => "Y",
                        "VARIABLE_ALIASES" => Array(),
                    )
                ); ?>
            </div>
        </div>
        <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
            <div class="inner js-paralax-item">
                <div class="padding">
                    <?
                    $form_id = "MAKE_REQUEST_" . strtoupper(LANGUAGE_ID);
                    if(BXHelper::can_show_form($form_id)) {
                        BXHelper::start_ajax_block();
                        $APPLICATION->IncludeComponent(
                            "bitrix:form.result.new",
                            "hogart_request",
                            Array(
                                "SEF_MODE" => "N",
                                "WEB_FORM_ID" => $form_id,
                                "LIST_URL" => "",
                                "EDIT_URL" => "",
                                "SUCCESS_URL" => "",
                                "CHAIN_ITEM_TEXT" => "",
                                "CHAIN_ITEM_LINK" => "",
                                "IGNORE_CUSTOM_TEMPLATE" => "N",
                                "USE_EXTENDED_ERRORS" => "N",
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600",
                                "CACHE_NOTES" => "",
                                "VARIABLE_ALIASES" => Array(
                                    "WEB_FORM_ID" => "WEB_FORM_ID",
                                    "RESULT_ID" => "RESULT_ID"
                                ),
                                "CUSTOM_INPUT_PARAMS" => array(
                                    "SOLUTION_EMAIL" => array(
                                        "data-rule-email" => "true",
                                        "data-msg-email" => "Введите правильный email адрес"
                                    ),
                                ),
                                "CUSTOM_WRAPPER_PARAMS" => array(
                                ),
                                "TITLE" => "Обратная связь",
                                "SUCCESS_RELOAD" => "N",
                                "SUCCESS_MESSAGE" => "Спасибо что обратились в нашу компанию! В ближайшее время с вами свяжется наш специалист для уточнения дополнительной информации."

                            )
                        );
                        BXHelper::end_ajax_block(false, false, false, false);
                    }
                    ?>
                </div>
            </div>
        </aside>
    </div>
    <br><? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>