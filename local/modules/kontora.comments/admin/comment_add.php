<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/include.php"); // инициализация модуля
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/admin_tools.php"); 
IncludeModuleLangFile(__FILE__);

global $USER, $APPLICATION;

//Статус
define('U_NEW', 1 << 0);  
define('U_AGREE', 1 << 1);

$POST_RIGHT = $APPLICATION->GetGroupRight("kontora.comments");
if ($POST_RIGHT == "D")
	// TODO(olesia): Завести переменную
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$aTabs = array(
	array(
		"DIV"   => "edit1", 
		"TAB"   => GetMessage("tab_comment"),
		"ICON"  => "main_user_edit", 
		"TITLE" => GetMessage("tab_comment"),
	),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$message = null;		// сообщение об ошибке
$bVarsFromForm = false; // флаг "Данные получены с формы", обозначающий, что выводимые данные получены с формы, а не из БД.

if ($REQUEST_METHOD == "POST" && ($save!="" || $apply!="") && $POST_RIGHT >= "R" && check_bitrix_sessid()) {
 	$right_key = 0;
	$level = 0;
	if (!isset($_REQUEST['commentid']) && empty($_REQUEST['commentid'])) {
		$right_key = CKontoraComments::GetMaxRightKey($_REQUEST['ELEMENT']['VALUE']) + 1;
	} else {
		$parent_comment = CKontoraComments::GetByID(intval($_REQUEST['commentid']));
		$right_key = $parent_comment['RIGHT_KEY'];
		$level = $parent_comment['LEVEL'];
		CKontoraComments::UpdateBeforeAdd($right_key);
	}
	$arFields = array(
		'COMMENT'    => $_REQUEST['COMMENT'],
		'USER_ID'    => $USER->GetID(),
		'ELEMENT_ID' => $_REQUEST['ELEMENT']['VALUE'],
		'LEFT_KEY'   => $right_key,
		'RIGHT_KEY'  => $right_key + 1,
		'LEVEL'      => $level + 1,
		'STATUS'     => U_AGREE,
	); 
   	$ID = CKontoraComments::Add($arFields);

   	$CACHE_MANAGER->ClearByTag("kontora_comments_" . $arFields['ELEMENT_ID']);
	$CACHE_MANAGER->ClearByTag("kontora_comments_user_" . $arFields['USER_ID']);

	if ($ID > 0)
		LocalRedirect("/bitrix/admin/comments_list.php?lang=".LANG);
	else
		if ($e = $APPLICATION->GetException())
	  		$message = new CAdminMessage(GetMessage("rub_save_error"), $e);
}

$str_ELEMENT = '';
$str_COMMENT = '';
$str_COMMENT_TYPE = 'text';

// если данные переданы из формы, инициализируем их
if ($bVarsFromForm)
	$DB->InitTableVarsForEdit("kontora_comments", "", "str_");

$APPLICATION->SetTitle(GetMessage("title"));

$aMenu = array(
	array(
		"TEXT"  => GetMessage("comments_list"),
		"TITLE" => GetMessage("comments_list"),
		"LINK"  => "comments_list.php?lang=".LANG,
		"ICON"  => "btn_list",
	)
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); // второй общий пролог

$context = new CAdminContextMenu($aMenu);
$context->Show();

if ($message)
	echo $message->Show();
elseif ($rubric->LAST_ERROR != "")
	CAdminMessage::ShowMessage($rubric->LAST_ERROR);
?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">
	<? echo bitrix_sessid_post(); ?>
	<input type="hidden" name="lang" value="<?=LANG?>">
	<? if (isset($_REQUEST['commentid']) && !empty($_REQUEST['commentid'])): ?>
		<input type="hidden" name="commentid" value="<?=$_REQUEST['commentid']?>">
	<? endif;

	$tabControl->Begin();
	$tabControl->BeginNextTab();
	?>
		<tr>
			<td width="40%"><? echo GetMessage("ELEMENT")?></td>
			<td width="60%"><? _ShowPropertyField('ELEMENT', array('PROPERTY_TYPE' => 'E'), array('VALUE' => $_REQUEST['elementid'])); ?></td>
		</tr>
		<tr class="heading" id="tr_COMMENT_LABEL">
			<td colspan="2"><? echo GetMessage("COMMENT")?></td>
		</tr>
		<tr id="tr_COMMENT">
			<td colspan="2" align="center">
				<textarea cols="60" rows="10" name="COMMENT" style="width:100%"><?echo $str_COMMENT?></textarea>
			</td>
		</tr>
	<?$tabControl->Buttons();
	?>
		<input <?if ($POST_RIGHT < "R") echo "disabled" ?> type="submit" name="save" value="<?=GetMessage("MAIN_SAVE")?>" class="adm-btn-save" id="save">
		<input <?if ($POST_RIGHT < "R") echo "disabled" ?> type="button" name="dontsave" id="dontsave" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes("comments_list.php?lang=".LANG))?>'">
			
		<?
		echo bitrix_sessid_post();
	$tabControl->End();?>
</form>
<?
$tabControl->ShowWarnings("post_form", $message);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>