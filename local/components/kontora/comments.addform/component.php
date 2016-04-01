<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

global $USER, $APPLICATION;

$module_id = 'kontora.comments';
$arResult['RIGHTS'] = $APPLICATION->GetGroupRight($module_id);

if (COption::GetOptionString($module_id, "wysiwyg") == 1) {
	$APPLICATION->SetAdditionalCSS("/bitrix/components/kontora/comments.addform/templates/.default/jquery.cleditor.css");
	$APPLICATION->AddHeadScript("/bitrix/js/".$module_id."/jquery.cleditor.min.js");
}

// prepare captcha
if (COption::GetOptionString($module_id, "captcha") == 1 && !$USER->IsAuthorized()) {
	$arResult['USE_CAPTHCA'] = 'Y';
	$arResult["CAPTCHA_CODE"] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
}

if ($arResult['RIGHTS'] >= 'N')
	$this->IncludeComponentTemplate();
else
	if (!$USER->IsAuthorized())
		$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
	else
		ShowError(GetMessage('ACCESS_GROUP_DENIED'));