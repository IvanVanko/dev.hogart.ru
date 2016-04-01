<?php

/*
$arResult['SECTIONS'] - массив, содержащий в себе массив из секций со значениями их полей и свойств
$arSection - массив секции с ключами:
$arSection['NAME']				Название
$arSection['SECTION_PAGE_URL']	Url детальной страницы
$arSection['PICTURE']			Путь до каринки
$arSection['DESCRIPTION']		Описание
$arSection['DETAIL_PICTURE']	Путь до детальной картинки
*/

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
if (count($arResult['SECTIONS']) > 0):
	if (isset($arParams['HTML_TYPE']))
		echo '<'.$arParams['HTML_TYPE'][0].'>';
	else
		echo '<ul>';
		foreach ($arResult["SECTIONS"] as $arSection):
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>
			<? if (isset($arParams['HTML_TYPE']))
				echo '<'.$arParams['HTML_TYPE'][1].' id="'.$this->GetEditAreaId($arItem['ID']).'">';
			else
				echo '<li id="'.$this->GetEditAreaId($arItem['ID']).'">';
			if (!isset($arParams['ITEM_TEMPLATE']) && empty($arParams['ITEM_TEMPLATE'])):?>
				<a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection['NAME']?></a>
				<? if (!empty($arSection["PICTURE"])): ?>
					<a href="<?=$arSection["SECTION_PAGE_URL"]?>"><img src="<?=$arSection['PICTURE']?>" alt="<?=$arSection['NAME']?>"/></a>
				<? endif; ?>
				<? if ($arSection["DESCRIPTION"]): ?>
					<?=$arSection["DESCRIPTION"]?>
				<? endif;
			else:
				echo htmlspecialchars_decode(str_replace(explode(",", "^".implode("^,^", array_keys($arSection))."^"), array_values($arSection), $arParams['ITEM_TEMPLATE']));
			endif;
			if (isset($arParams['HTML_TYPE']))
				echo '<'.$arParams['HTML_TYPE'][2].'>';
			else
				echo '</li>';
		endforeach;
	if (isset($arParams['HTML_TYPE']))
		echo '<'.$arParams['HTML_TYPE'][3].'>';
	else
		echo '</ul>';
	if ($arParams["NAV"] == 'Y')
		echo $arResult["NAV_STRING"];
endif; ?>