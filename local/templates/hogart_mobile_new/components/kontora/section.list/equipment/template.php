<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
if (count($arResult['SECTIONS']) > 0):?>
	<div class="col2">
        <ul class="selection-equip-tab js-tabs-list">
            <li><a href="#" data-show="selection-equip-group" class="js-tab-trigger">
                    Показать все листы
                </a></li>
			<?foreach ($arResult["SECTIONS"] as $key => $arSection):?>
				<li>
                    <a href="#tab<?=$key+1?>" data-group="selection-equip-group" class="js-tab-trigger<?=($_REQUEST['direction'] == $arSection['UF_CATALOG_SECTION'])?' active':''?>">
					<?=$arSection["NAME"]?>
				    </a>
                </li>
			<?endforeach;?>
		</ul>
	</div>
<?endif; ?>