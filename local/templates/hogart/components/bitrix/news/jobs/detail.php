<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
$vacancy = CIBlockElement::GetList([], ['CODE' => $arResult["VARIABLES"]["ELEMENT_CODE"],
                             'IBLOCK_ID' => $arParams['IBLOCK_ID']], false, false)->GetNext();

$vacancyName = $vacancy['NAME'];
?>
<? if(!empty($arResult["VARIABLES"]["ELEMENT_CODE"])) { ?>
    <div class="row">
        <div class="col-md-9 col-xs-12">
            <? $ElementID = $APPLICATION->IncludeComponent(
                "kontora:element.detail",
                "",
                Array(
                    "CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
                    "BACK_URL" => $arParams['SEF_FOLDER'],
                    'PROPS' => 'Y',
                    "SET_TITLE" => $arParams['SET_TITLE'],
                    "CACHE_TIME" => "0",
                    'SET_STATUS_404' => $arParams['SET_STATUS_404']
                ),
                $component
            ); ?>
        </div>
        <div class="col-md-3 col-xs-12 aside-mobile">
            <? $APPLICATION->IncludeComponent(
                "bitrix:form.result.new",
                "",
                Array(
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
                    "VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),

                    "SUCCESS_MESSAGE" => "Спасибо, что обратились в нашу компанию! Ваша заявка на вакансию \"{$vacancyName}\" принята. В ближайшее время с вами свяжется специалист по кадрам."
                ), $component
            ); ?>
        </div>
    </div>
<? }
else {
    BXHelper::NotFound();
} ?>
