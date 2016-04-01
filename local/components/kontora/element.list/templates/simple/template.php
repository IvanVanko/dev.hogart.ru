<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (count($arResult['ITEMS']) > 0):?>
	<ul>
		<?foreach ($arResult["ITEMS"] as $arItem):
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem['NAME']?></a>
				<? if (!empty($arItem["PREVIEW_PICTURE"])): ?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem['PREVIEW_PICTURE']?>" alt="<?=$arItem['NAME']?>"/></a>
				<? endif; ?>
				<? if ($arItem["PREVIEW_TEXT"]): ?>
					<?=$arItem["PREVIEW_TEXT"]?>
				<? endif; ?>
			</li>
		<?endforeach;?>
	</ul>
<?endif; ?>