<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?if (!empty($arResult["VARIABLES"]["ELEMENT_CODE"])) {?>
    <div class="inner">
        <h1><?$APPLICATION->ShowTitle()?></h1>
        <?$ElementID = $APPLICATION->IncludeComponent(
            "kontora:element.detail",
            "",
            Array(
                "CODE"       => $arResult["VARIABLES"]["ELEMENT_CODE"],
                "BACK_URL" => $arParams['SEF_FOLDER'],
                'PROPS'    => 'Y',
                'SET_STATUS_404' => $arParams['SET_STATUS_404']
            ),
            $component
        );?>
    </div>
    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="sidebar-vacancy left-text">
                <a class="side-back" href="/company/jobs/">
                    Ко всем вакансиям
                    <i class="icon-white-back"></i>
                </a>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:form.result.new",
                    "",
//				"job-form",
                    Array(
//                    "AJAX_MODE" => "Y",  // режим AJAX
//                    "AJAX_OPTION_SHADOW" => "N", // затемнять область
//                    "AJAX_OPTION_JUMP" => "Y", // скроллить страницу до компонента
//                    "AJAX_OPTION_STYLE" => "Y", // подключать стили
//                    "AJAX_OPTION_HISTORY" => "N",
                        "WEB_FORM_ID" => "3",
                        "IGNORE_CUSTOM_TEMPLATE" => "Y",
                        "USE_EXTENDED_ERRORS" => "N",
                        "SEF_MODE" => "N",
                        "CACHE_TIME" => "3600",
                        "CACHE_TYPE" => "A",
                        "LIST_URL" => "",
                        "EDIT_URL" => "",
                        "SUCCESS_URL" => "",
                        "CHAIN_ITEM_TEXT" => "",
                        "CHAIN_ITEM_LINK" => "",
                        "VARIABLE_ALIASES" => Array("WEB_FORM_ID"=>"WEB_FORM_ID","RESULT_ID"=>"RESULT_ID"),

                        "SUCCESS_MESSAGE" => "Спасибо что обратились к нам! Ваша информация принята к рассмотрению. В ближайшее время с вами свяжется специалист по кадрам.",
                        "SHOW_REQUIRED_MESSAGE" => "Y",
                        "REQUIRED_MESSAGE" => "Все поля обязательны для заполнения"
                    ), $component
                );?>
            </div>
        </div>
    </aside>
<?} else {
    BXHelper::NotFound();
}?>
