<?php

class CKontoraComments {
	function GetList($aFilter = array(), $group = false) {
		global $DB;

		$arFilter = array();
		$groupBy = ($group == false) ? '' : ' GROUP BY '.$group;
		foreach ($aFilter as $key => $val) {
			$val = $DB->ForSql($val);
			if (strlen($val) <= 0) continue;
			$arFilter[] = "kontora_comments.".$key." = '" . $val . "'";
		}

		$sFilter = (count($arFilter) == 0) ? "" : "\nWHERE " . implode("\nAND ", $arFilter);
		$strSql = "SELECT 
						kontora_comments.ID, kontora_comments.ELEMENT_ID, kontora_comments.USER_ID, kontora_comments.MODERATOR_ID,
						kontora_comments.STATUS, kontora_comments.DATE_CREATE, kontora_comments.DATE_LAST_CHANGE,
						kontora_comments.LEFT_KEY, kontora_comments.RIGHT_KEY, kontora_comments.LEVEL, kontora_comments.COMMENT,
						b_iblock_element.NAME AS ELEMENT_NAME, b_iblock_element.IBLOCK_ID,
						b_user.NAME AS USER_NAME, b_user.LAST_NAME, b_user.LOGIN, b_user.SECOND_NAME, b_user.PERSONAL_PHOTO
					FROM kontora_comments 
						LEFT JOIN b_iblock_element ON ELEMENT_ID = b_iblock_element.ID 
						LEFT JOIN b_user ON USER_ID = b_user.ID 
					".$sFilter.$groupBy." ORDER BY LEFT_KEY";

		return $DB->Query($strSql);
	}

	function Add($arFields) {
		global $DB, $USER;

		$arFields['IP'] = $_SERVER['REMOTE_ADDR'];
		$arInsert = $DB->PrepareInsert("kontora_comments", $arFields);
		$strSql = "INSERT INTO kontora_comments (" . $arInsert[0] . ") VALUES (" . $arInsert[1] . ")";
		$DB->Query($strSql);
		$ID = intval($DB->LastID());

		if (COption::GetOptionString('kontora.comments','send_to_email') == 1) {
			CModule::IncludeModule("iblock");
			$res = CIBlockElement::GetByID($arFields['ELEMENT_ID']);
			$arElement = $res->GetNext();

			$rsComment = CKontoraComments::GetList(array('ID' => $ID));
			$arComment = $rsComment->Fetch();

			$arMailFields = array(
				'EMAIL_TO'    => COption::GetOptionString('main','email_from'),
				'USER_NAME'   => $arComment['USER_NAME'].' '.$arComment['LAST_NAME'],
				'DATE_CREATE' => $arComment['DATE_CREATE'],
				'COMMENT'     => $arComment['COMMENT'],
				'ELEMENT_URL' => $arElement['DETAIL_PAGE_URL'],
			);
			CEvent::Send('ADD_COMMENT', SITE_ID, $arMailFields);
		}
		
		return $ID;
	}

	function GetMaxRightKey($elementID) {
		global $DB;

		$query = $DB->Query('SELECT MAX(RIGHT_KEY) FROM kontora_comments WHERE ELEMENT_ID='.intval($elementID));
		$maxRightKey = $query->Fetch();
		
		return $maxRightKey['MAX(RIGHT_KEY)'];
	}

	function UpdateBeforeAdd($rightKey) {
		global $DB;
		
		$query = $DB->Query("UPDATE kontora_comments SET RIGHT_KEY = RIGHT_KEY + 2, LEFT_KEY = IF(LEFT_KEY > ".intval($rightKey).", LEFT_KEY + 2, LEFT_KEY) WHERE RIGHT_KEY >= ".intval($rightKey));
		
		return;
	}

	function Delete($commentID) {
		global $DB;
		
		$arComment = CKontoraComments::GetByID($commentID);
		$DB->Query('DELETE FROM kontora_comments WHERE LEFT_KEY >= '.intval($arComment['LEFT_KEY']).' AND RIGHT_KEY <= '.intval($arComment['RIGHT_KEY']));
		$DB->Query('UPDATE kontora_comments SET LEFT_KEY = IF(LEFT_KEY > '.intval($arComment['LEFT_KEY']).', LEFT_KEY - '.(intval($arComment['RIGHT_KEY']) - intval($arComment['LEFT_KEY']) + 1).', LEFT_KEY), RIGHT_KEY = RIGHT_KEY - '.(intval($arComment['RIGHT_KEY']) - intval($arComment['LEFT_KEY']) + 1).' WHERE RIGHT_KEY > '.intval($arComment['RIGHT_KEY']));
		
		return true;
	}

	function GetByID($commentID) {
		global $DB;
		
		$query = $DB->Query('SELECT * FROM kontora_comments WHERE ID='.intval($commentID));
		
		return $query->Fetch();
	}

	function SetStatus($commentID, $status) {
		global $DB, $USER;
		
		$userID = $USER->GetID();
		$query = $DB->Query('UPDATE kontora_comments SET STATUS = '.$status.', MODERATOR_ID = '.$userID.' WHERE ID='.intval($commentID));
		
		return;
	}

	function Update($ID, $arFields)
	{
		global $DB;
		
		$ID = intval($ID);
		$strUpdate = $DB->PrepareUpdate("kontora_comments", $arFields);
		$strSql = "UPDATE kontora_comments SET " . $strUpdate . " WHERE ID = " . $ID;
		$DB->Query($strSql, false, "FILE: " . __FILE__ . "<br> LINE: " . __LINE__);

		return true;
	}
}