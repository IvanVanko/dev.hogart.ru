<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//Статусы
define('U_NEW', 1 << 0);  
define('U_AGREE', 1 << 1);

global $CACHE_MANAGER, $USER, $APPLICATION;

$module_id = 'kontora.comments';
$arResult['RIGHTS'] = $APPLICATION->GetGroupRight($module_id);

$arResult['DISAGREE_COMMENTS'] = (COption::GetOptionString($module_id, "disagree") == 1) ? 'Y' : 'N';

$left_key1 = 0; 
$left_key2 = 0;

if (!isset($arParams["CACHE_TIME"])) $arParams["CACHE_TIME"] = 36000000;

if ($arParams['CACHE_TYPE'] == "A" || $arParams['CACHE_TYPE'] == "Y") {
	$cache_name = (isset($arParams['FILTER']['ELEMENT_ID'])) ? $arParams['FILTER']['ELEMENT_ID'] : 'user_'.$arParams['FILTER']['USER_ID'];
	$cache_id = serialize(array($cache_name, (($arResult['RIGHTS'] < 'R')?true:false)));
	$cache_path = "/".SITE_ID.'/'.str_replace(':', '/', $this->GetName())."/".$cache_name;
}

if (COption::GetOptionString($module_id, "wysiwyg") == 1) {
	$arResult['USE_WYSIWYG'] = 'Y';
	$APPLICATION->SetAdditionalCSS("/bitrix/components/kontora/comments.addform/templates/.default/jquery.cleditor.css");
	$APPLICATION->AddHeadScript("/bitrix/js/".$module_id."/jquery.cleditor.min.js");
}

if (CModule::IncludeModule($module_id) && $arResult['RIGHTS'] != 'D') {
	if ($this->StartResultCache(false, $cache_id, $cache_path)) {
			// caching
			$CACHE_MANAGER->StartTagCache($cache_path);
			$CACHE_MANAGER->RegisterTag("kontora_comments_" . $cache_name);

		$rsComment = CKontoraComments::GetList($arParams['FILTER']);
		while ($arComment = $rsComment->Fetch()) {
			$arComment['PERSONAL_PHOTO'] = CFile::GetPath($arComment['PERSONAL_PHOTO']);
			if (!($arComment['STATUS'] & (U_AGREE | U_NEW)) && $arResult['RIGHTS'] >= 'K' && $arResult['RIGHTS'] < 'R')
				if (COption::GetOptionString($module_id, "disagree") == 1) {
					$arComment['COMMENT'] = GetMessage('DELETED_COMMENT');
					$arComment['DELETED'] = 'Y';	
				} else {
					$left_key1 = $arComment['LEFT_KEY'];
					$left_key2 = $arComment['RIGHT_KEY'];
				}

			if ($left_key1 != 0 && $arComment['LEFT_KEY'] > $left_key1 && $arComment['LEFT_KEY'] < $left_key2)
				continue;

			if (($arResult['RIGHTS'] >= 'K' && $arResult['RIGHTS'] < 'R') &&
				(
					($arComment['STATUS'] & U_NEW && COption::GetOptionString($module_id, "premoderation") == 0) ||
					($arComment['STATUS'] & U_AGREE) ||
					(!($arComment['STATUS'] & (U_AGREE | U_NEW)) && $arResult['DISAGREE_COMMENTS'] == 'Y')
				))
					$arResult['COMMENTS'][] = $arComment;
			elseif (($arResult['RIGHTS'] >= 'R') &&
				(
					($arComment['STATUS'] & U_NEW) ||
					($arComment['STATUS'] & U_AGREE) ||
					(!($arComment['STATUS'] & (U_AGREE | U_NEW)) && COption::GetOptionString($module_id, "show_disagree") == 1)
				))
					$arResult['COMMENTS'][] = $arComment;
		
		}
		$this->IncludeComponentTemplate();
	}
} else {
	if (!$USER->IsAuthorized())
		$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
	else
		ShowError(GetMessage('ERROR_GROUP_PERMISSIONS'));
}