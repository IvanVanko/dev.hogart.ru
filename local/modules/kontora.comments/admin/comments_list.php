<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/include.php");
IncludeModuleLangFile(__FILE__);

$sTableID = "kontora_comments";
$oSort = new CAdminSorting($sTableID, "LEFT_KEY", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

//Статусы
define('U_NEW', 1 << 0);  
define('U_AGREE', 1 << 1);

global $CACHE_MANAGER;

//Фильтр
$FilterArr = array(
    "find_id",
    'find_element_id',
    'find_status',
    'find_user_id',
);
$lAdmin->InitFilter($FilterArr);
$arFilter = array(
    "ID"         => $find_id,
    "ELEMENT_ID" => $find_element_id,
    "STATUS"     => $find_status,
    'USER_ID'    => $find_user_id,
);

$POST_RIGHT = $APPLICATION->GetGroupRight("kontora.comments");
// сохранение отредактированных элементов
if ($lAdmin->EditAction() && $POST_RIGHT >= "R") {
    foreach ($FIELDS as $ID=>$arFields) {
        if (!$lAdmin->IsUpdated($ID)) continue;
        
        $DB->StartTransaction();
        $ID = IntVal($ID);
        if ($arData = CKontoraComments::GetByID($ID)) {
            foreach ($arFields as $key => $value)
            	$arData[$key] = $value;

            if (!CKontoraComments::Update($ID, $arData)) {
                $lAdmin->AddGroupError(GetMessage("rub_save_error")." ".$cData->LAST_ERROR, $ID);
                $DB->Rollback();
            }

            $CACHE_MANAGER->ClearByTag("kontora_comments_" . $arData['ELEMENT_ID']);
	        $CACHE_MANAGER->ClearByTag("kontora_comments_user_" . $arData['USER_ID']);
        } else {
            $lAdmin->AddGroupError(GetMessage("rub_save_error")." ".GetMessage("rub_no_rubric"), $ID);
            $DB->Rollback();
        }
        $DB->Commit();
    }
}

// обработка одиночных и групповых действий
if (($arID = $lAdmin->GroupAction()) && $POST_RIGHT >= "R") {
    if ($_REQUEST['action_target'] == 'selected') {
        $rsData = CKontoraComments::GetList($arFilter);
        while ($arRes = $rsData->Fetch())
            $arID[] = $arRes['ID'];
    }

    foreach ($arID as $ID) {
        if (strlen($ID) <= 0) continue;
       	$ID = IntVal($ID);
        
        switch ($_REQUEST['action']) {
	        case "delete":
	            @set_time_limit(0);
	            $DB->StartTransaction();
	            if (!CKontoraComments::Delete($ID)) {
	                $DB->Rollback();
	                $lAdmin->AddGroupError(GetMessage("rub_del_err"), $ID);
	            }
	            $DB->Commit();
	            $CACHE_MANAGER->ClearByTag("kontora_comments_" . $_REQUEST['element']);
	        	$CACHE_MANAGER->ClearByTag("kontora_comments_user_" . $_REQUEST['user']);
	            break;
	        case "agree":
	        	CKontoraComments::SetStatus($ID, 'STATUS & ~'.U_NEW.' | '.U_AGREE);
	        	$CACHE_MANAGER->ClearByTag("kontora_comments_" . $_REQUEST['element']);
	        	$CACHE_MANAGER->ClearByTag("kontora_comments_user_" . $_REQUEST['user']);
	        	break;
	        case "disagree":
	        	CKontoraComments::SetStatus($ID, 'STATUS & ~'.U_NEW.' & ~'.U_AGREE);
	        	$CACHE_MANAGER->ClearByTag("kontora_comments_" . $_REQUEST['element']);
	        	$CACHE_MANAGER->ClearByTag("kontora_comments_user_" . $_REQUEST['user']);
	            break;
        }

    }
}

//Выберем список комментариев
$cData = new CKontoraComments;
$rsData = $cData->GetList($arFilter);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage("rub_nav"))); //!!!!!!

$lAdmin->AddHeaders(array(
	array( 
		"id"      => "ID",
		"content" => "ID",
		"default" => true,
	),
	array(  
		"id"      => "COMMENT",
		"content" => GetMessage("FIELD_COMMENT"),
		"default" => true,
	),
	array(  
		"id"      => "ELEMENT_ID",
		"content" => GetMessage("FIELD_ELEMENT"),
		"default" => true,
	),
	array( 
		"id"      => "USER_ID",
		"content" => GetMessage("FIELD_USER"),
		"default" => true,
	),
	array(  
		"id"      => "STATUS",
		"content" => GetMessage("FIELD_STATUS"),
		"default" => true,
	),
	array(
		"id"      => "VOTE",
		"content" => GetMessage("VOTING"),
		"default" => true
	),
));

while ($arRes = $rsData->NavNext(true, "f_")) {
	$row =& $lAdmin->AddRow($f_ID, $arRes); 
	$row->AddInputField("COMMENT", array("size" => 20));
	$tab = '';
	if (intval($f_LEVEL) > 1) 
		$tab = str_repeat("|-- ", ($f_LEVEL - 1));

	$row->AddViewField("COMMENT", $tab.' '.$f_COMMENT);

	$res = CIBlock::GetByID($f_IBLOCK_ID);
	$ar_res = $res->GetNext();
  	$row->AddViewField("ELEMENT_ID", '<a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$f_IBLOCK_ID.'&type='.$ar_res['IBLOCK_TYPE_ID'].'&ID='.$f_ELEMENT_ID.'&lang=ru&find_section_section=-1&WF=Y">'.$f_ELEMENT_ID.' ['.$f_ELEMENT_NAME.']</a>');

  	//Имя и ссылка на пользователя
  	$row->AddViewField("USER_ID", '<a href="/bitrix/admin/user_edit.php?lang=ru&ID='.$f_USER_ID.'">'.$f_USER_ID.' ['.$f_USER_NAME.' '.$f_LAST_NAME.']</a>');
  
  	//Статус
	if ($f_STATUS & U_NEW)
		$status = GetMessage("STATUS_NEW");
	elseif ($f_STATUS & U_AGREE)
		$status = GetMessage("STATUS_AGREE");
	else
		$status = GetMessage("STATUS_DISAGREE");
	$row->AddViewField("STATUS", $status);

	//Голосование
	$voting  = CRatings::GetRatingVoteResult('COMMENTS', $f_ID);
	$positive_votes = ($voting['TOTAL_POSITIVE_VOTES'])? $voting['TOTAL_POSITIVE_VOTES'] : 0;
	$negative_votes = ($voting['TOTAL_NEGATIVE_VOTES'])? $voting['TOTAL_NEGATIVE_VOTES'] : 0;
	$row->AddViewField("VOTE", $positive_votes.' Да / '.$negative_votes.' Нет');

  	// сформируем контекстное меню
  	$arActions = array();
	
	$arActions[] = array(
		"ICON"   => "add",
		"TEXT"   => GetMessage("ACTION_ADD"),
		"ACTION" => $lAdmin->ActionRedirect("/bitrix/admin/comment_add.php?lang=".LANG."&commentid=".$f_ID.'&elementid='.$f_ELEMENT_ID),
	);
	$arActions[] = array("SEPARATOR" => true);
	$arActions[] = array(
		"ICON"   => "edit",
		"TEXT"   => GetMessage("ACTION_AGREE"),
		"ACTION" => $lAdmin->ActionDoGroup($f_ID, "agree", 'element='.$f_ELEMENT_ID.'&user='.$f_USER_ID)
	);
	$arActions[] = array("SEPARATOR" => true);
	$arActions[] = array(
		"ICON"   => "edit",
		"TEXT"   => GetMessage("ACTION_DISAGREE"),
		"ACTION" => $lAdmin->ActionDoGroup($f_ID, "disagree", 'element='.$f_ELEMENT_ID.'&user='.$f_USER_ID)
	);
	$arActions[] = array("SEPARATOR" => true);
	$arActions[] = array(
		"ICON"   => "delete",
		"TEXT"   => GetMessage("ACTION_DELETE"),
		"ACTION" => "if(confirm('".GetMessage("ACTION_DELETE")."?'))".$lAdmin->ActionDoGroup($f_ID, "delete", 'element='.$f_ELEMENT_ID.'&user='.$f_USER_ID)
	);	

	// если последний элемент - разделитель, почистим мусор.
	if (is_set($arActions[count($arActions) - 1], "SEPARATOR")) 
		unset($arActions[count($arActions) - 1]);
	
	$row->AddActions($arActions);
}

// резюме таблицы
$lAdmin->AddFooter(array(
    array(
    	"title" => GetMessage("TITLE"),
    	"value" => $rsData->SelectedRowsCount()
    ),
    array(
    	"counter" => true, 
    	"title"   => GetMessage("MAIN_ADMIN_LIST_CHECKED"),
    	"value"	  => "0"
    ), 
));

$lAdmin->AddGroupActionTable(
	array(
		"delete"   => GetMessage("ACTION_DELETE"),
		"agree"    => GetMessage("ACTION_AGREE"),
		"disagree" => GetMessage("ACTION_DISAGREE")
	)
);

//ДОБАВИТЬ КОММЕНТАРИЙ
$aContext = array(
	array(
		"TEXT"  => "Добавить комментарий",
		"LINK"  => "comment_add.php?lang=".LANG,
		"TITLE" => "Добавить комментарий",
		"ICON"  => "btn_new",
	),
);
$lAdmin->AddAdminContextMenu($aContext);
$lAdmin->CheckListMode();
$APPLICATION->SetTitle(GetMessage("TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php"); // второй общий пролог

// создадим объект фильтра
$oFilter = new CAdminFilter(
	$sTableID."_filter",
	array(
		"ID",
		"ELEMENT_ID",
		"STATUS",
	)
);
?>
<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
	<?$oFilter->Begin();?>
		<tr>
			<td>ID:</td>
			<td><input type="text" name="find_id" size="47" value="<? echo htmlspecialchars($find_id) ?>"></td>
		</tr>
		<tr>
			<td>Элемент:</td>
			<td>
				<?
				$rsComment = CKontoraComments::GetList(array(), 'ELEMENT_ID');
				while ($arComment = $rsComment->Fetch()) {
					$arElementsID[] = $arComment['ELEMENT_ID'];
					$arElementsName[] = $arComment['ELEMENT_ID'].' ['.$arComment['ELEMENT_NAME'].']';
				}
				$arrElemenID = array(
					"reference"    => $arElementsName,
					"reference_id" => $arElementsID,
				);
				echo SelectBoxFromArray("find_element_id", $arrElemenID, $find_element_id, "Все", "");
				?>
			</td>
		</tr>
		<tr>
			<td>Статус:</td>
			<td>
				<?$arr = array(
					"reference" => array(
						GetMessage("FILTER_STATUS_NEW"),
						GetMessage("FILTER_STATUS_AGREE"),
						GetMessage("FILTER_STATUS_DISAGREE")
					),
					"reference_id" => array(
						U_NEW,
						U_AGREE,
						~U_NEW & ~ U_AGREE
					)
				);
				echo SelectBoxFromArray("find_status", $arr, $find_status, "Все", "");?>
			</td>
		</tr>
		<tr>
			<td>Пользователь:</td>
			<td>
				<?
				$rsCommentUser = CKontoraComments::GetList(array(), 'USER_ID');
				while ($arCommentUser = $rsCommentUser->Fetch()) {
					$arUserID[] = $arCommentUser['USER_ID'];
					$arUserName[] = $arCommentUser['USER_ID'].' ['.$arCommentUser['USER_NAME'].' '.$arCommentUser['LAST_NAME'].']';
				}
				$arrUserID = array(
					"reference"    => $arUserName,
					"reference_id" => $arUserID,
				);
				echo SelectBoxFromArray("find_user_id", $arrUserID, $find_user_id, "Все", "");
				?>
			</td>
		</tr>
	<?
	$oFilter->Buttons(array("table_id"=>$sTableID,"url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
	$oFilter->End();
	?>
</form>
<?
// выведем таблицу списка элементов
$lAdmin->DisplayList();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>