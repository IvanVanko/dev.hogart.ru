<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
if (count($arResult['SECTIONS']) > 0):?>
	<ul>
		<?foreach ($arResult["SECTIONS"] as $arSection):
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>
			<li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
				<a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection['NAME']?></a>
				<? if (!empty($arSection["PICTURE"])): ?>
					<a href="<?=$arSection["SECTION_PAGE_URL"]?>"><img src="<?=$arSection['PICTURE']?>" alt="<?=$arSection['NAME']?>"/></a>
				<? endif; ?>
				<? if ($arSection["DESCRIPTION"]): ?>
					<?=$arSection["DESCRIPTION"]?>
				<? endif;?>
			</li>
		<?endforeach;?>
	</ul>
<?endif; ?>