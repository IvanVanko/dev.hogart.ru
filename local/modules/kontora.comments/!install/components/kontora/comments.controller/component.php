<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//Статус
define('U_NEW', 1 << 0);  
define('U_AGREE', 1 << 1);

global $CACHE_MANAGER, $USER, $APPLICATION;

$right_key = 0;
$level = 0;

$module_id = 'kontora.comments';
$rights = $APPLICATION->GetGroupRight($module_id);

$cache_name = (isset($arParams['ELEMENT_ID'])) ? $arParams['ELEMENT_ID'] : 'user_'.$arParams['USER_ID'];
if (CModule::IncludeModule($module_id)) {
	//Добавление нового комментария
	if (isset($_REQUEST['add_comment']) && !empty($_REQUEST['add_comment']) && $rights >= 'N') {
		// check captcha
		if (COption::GetOptionString($module_id, "captcha") == 1 && !$USER->IsAuthorized() && !$APPLICATION->CaptchaCheckCode($_REQUEST["captcha_word"], $_REQUEST["captcha_sid"]))
			$arResult["ERRORS"][] = GetMessage('CAPTHCA_ERROR');
		
		if (empty($arResult["ERRORS"])) {
			if (!isset($_REQUEST['comment_id']) && empty($_REQUEST['comment_id'])) {
				$right_key = CKontoraComments::GetMaxRightKey($arParams['ELEMENT_ID']) + 1;
			} else {
				$parent_comment = CKontoraComments::GetByID($_REQUEST['comment_id']);
				$right_key = $parent_comment['RIGHT_KEY'];
				$level = $parent_comment['LEVEL'];
				CKontoraComments::UpdateBeforeAdd($right_key);
			}
			$arFields = array(
				'COMMENT' 		=> $_REQUEST['comment'],
				'USER_ID' 		=> $USER->GetID(),
				'ELEMENT_ID' 	=> $arParams['ELEMENT_ID'],
				'LEFT_KEY' 		=> $right_key,
				'RIGHT_KEY'		=> $right_key + 1,
				'LEVEL' 		=> $level + 1,
			); 
			$commentID = CKontoraComments::Add($arFields);
			unset($_REQUEST['comment']);

			$CACHE_MANAGER->ClearByTag("kontora_comments_" . $cache_name);
			LocalRedirect($_SERVER['REQUEST_URI']);
		} else {
			ShowError(implode("<br />", $arResult["ERRORS"]));
		}
	}

	//Удаление комментария
	if (isset($_REQUEST['delete']) && !empty($_REQUEST['delete']) && $rights >= 'R') {
		CKontoraComments::Delete($_REQUEST['delete']);
		$CACHE_MANAGER->ClearByTag("kontora_comments_" . $cache_name);
		LocalRedirect($APPLICATION->GetCurPageParam("", array("delete")));
	}

	//Одобрить/Неодобрить
	if (isset($_REQUEST['agree']) && !empty($_REQUEST['agree']) && $rights >= 'R') {
		$status = $_REQUEST['status'];
		$status &= ~U_NEW;
		if ($_REQUEST['agree'] == 'y') 
			$status |= U_AGREE;
		CKontoraComments::SetStatus($_REQUEST['id'], $status);
		$CACHE_MANAGER->ClearByTag("kontora_comments_" . $cache_name);
		LocalRedirect($APPLICATION->GetCurPageParam("", array("agree", "id", "status")));
	}

	//Редактирование комментария
	if (isset($_REQUEST['edit']) && !empty($_REQUEST['edit']) && $rights >= 'N') {
		$arData = array('COMMENT' => $_REQUEST['comment']);
		CKontoraComments::Update($_REQUEST['comment_id'], $arData);
		$CACHE_MANAGER->ClearByTag("kontora_comments_" . $cache_name);
		LocalRedirect($_SERVER['REQUEST_URI']);
	}
	
	$this->IncludeComponentTemplate();
}