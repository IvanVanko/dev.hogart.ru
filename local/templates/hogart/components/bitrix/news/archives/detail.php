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
<?if ($arResult["VARIABLES"]["ELEMENT_CODE"]) {?>
    <?$ElementID = $APPLICATION->IncludeComponent("kontora:element.detail", "", array(
            "CODE"         => $arResult["VARIABLES"]["ELEMENT_CODE"],
            'PROPS'      => 'Y',
            'SEF_FOLDER' => $arParams['SEF_FOLDER'],
            'ORDER'      => $arParams['ORDER'],
            'IBLOCK_ID'  => $arParams['IBLOCK_ID'],
            'SET_STATUS_404' => $arParams['SET_STATUS_404']
        ),
        $component
    );?>
    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <?$APPLICATION->IncludeComponent(
                "kontora:element.list",
                "stock_detail",
                Array(
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "PROPS"     => "Y",
                    "FILTER"    => array('!ID' => $ElementID),
                    'SEF_FOLDER' => $arParams['SEF_FOLDER'],
                    'ORDER'         => $arParams['ORDER'],
                ),
                $component
            );?>
            <div class="side_href">
                <a href="#" class="icon-email">Отправить на e-mail</a>
                <a href="#" class="icon-print">Распечатать</a>
                <a href="#" class="icon-phone">Отправить SMS</a>
            </div>
        </div>
    </aside>
<?} else {
    BXHelper::NotFound();
}?>
