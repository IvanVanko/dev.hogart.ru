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

<? if ($APPLICATION->GetCurDir() === $arParams["SEF_FOLDER"]): ?>
<div class="row">
    <div class="col-md-9">
        <h3><?$APPLICATION->ShowTitle()?></h3>
        <p>
            <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                    "AREA_FILE_SHOW" => "sect",
                    "AREA_FILE_SUFFIX" => "inc_jobs",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                )
            );?>
        </p>
        <?$APPLICATION->IncludeComponent(
            "kontora:element.list",
            "",
            Array(
                "IBLOCK_ID"	    => $arParams["IBLOCK_ID"],
                'ORDER'         => array('sort' => 'asc'),
                'ELEMENT_COUNT' => 20,
                'NAV'           => 'Y',
            ),
            $component
        );?>
    </div>
    <div class="col-md-3 aside">
        <?$APPLICATION->IncludeComponent(
            "kontora:element.list",
            "history",
            Array(
                "IBLOCK_ID"     => 14,
                'ORDER'         => array('sort' => 'asc'),
                'PROPS'         => 'Y',
                'ELEMENT_COUNT' => 2
            ),
            $component
        );?>
    </div>
</div>
    
<? else: ?>
    BXHelper::NotFound();
<? endif; ?>