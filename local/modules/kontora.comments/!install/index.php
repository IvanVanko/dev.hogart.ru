<?php

IncludeModuleLangFile(__FILE__);

Class kontora_comments extends CModule {
	var $MODULE_ID = "kontora.comments";
	var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_VERSION = 0;
    var $MODULE_VERSION_DATE = 0;
    var $MODULE_GROUP_RIGHTS = 'Y';

	function kontora_comments() {
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = GetMessage("MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("MODULE_DESCRIPTION");
	}

	function InstallDB() {
		global $DB, $DBType, $APPLICATION;
		
		$errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/install/db/".$DBType."/install.sql");
		if (!empty($errors)) {
			$APPLICATION->ThrowException(implode("", $errors));
			return false;
		} else {	
			RegisterModule("kontora.comments");
			return true;
		}
	}

	function UnInstallDB() {
		global $DB, $DBType, $APPLICATION;
		
		$errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/install/db/".$DBType."/uninstall.sql");
		if (!empty($errors)) {
			$APPLICATION->ThrowException(implode("", $errors));
			return false;
		} else {
			UnRegisterModule("kontora.comments");
			return true;
		}
	}

	function InstallFiles() {
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		
		return true;
	}

	function UnInstallFiles() {
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		DeleteDirFilesEx("/bitrix/js/kontora.comments/");//javascript

		return true;
	}

	function InstallCEvent() {
		//Создание типа почтового события
		$obEventType = new CEventType;
		$obEventType->Add(array(
		    "EVENT_NAME"  => "ADD_COMMENT",
		    "NAME"        => GetMessage("ADD_COMMENT"),
		    "LID"         => "ru",
		    "DESCRIPTION" => "
		        #ID# - ID комментария
		        #COMMENT# - Комментарий
		    	#USER_ID# - ID пользователя, написавшего комментарий
		        #USER_NAME# - Имя пользователя, написавшего комментарий
		    	#ELEMENT_ID# - ID элемента
		    	#ELEMENT_NAME# - Название элемента
		    	#DATE_CREATE# - Дата создания
		    	#EMAIL_TO# - Кому
		    	#EMAIL_FROM# - От кого
		   		#DEFAULT_EMAIL_FROM# - E-Mail адрес по умолчанию (устанавливается в настройках)
				#SITE_NAME# - Название сайта (устанавливается в настройках)
				#SERVER_NAME# - URL сервера (устанавливается в настройках)
		    	#ELEMENT_URL# - Ссылка на элемент с комментарием
		        "
		));

		//Создание почтового шаблона
		$rsSites = CSite::GetList($by="sort", $order="desc", Array());
		while ($arSite = $rsSites->Fetch())
			$arSites[] = $arSite['LID'];

		$obTemplate = new CEventMessage;
		$obTemplate->Add(array(
			"ACTIVE"     => "Y",
			"EVENT_NAME" => 'ADD_COMMENT',
			"LID"        => $arSites,
			"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
			"EMAIL_TO"   => "#EMAIL_TO#",
			"BCC"        => "",
			"SUBJECT"    => GetMessage("ADD_COMMENT"),
			"BODY_TYPE"  => "html",
			"MESSAGE"    => "
Добавлен новый комментарий.
Пользователь : #USER_NAME#,
Дата и время создания : #DATE_CREATE#,
Комметарий : #COMMENT#
<a href='http://#SERVER_NAME##ELEMENT_URL#'>http://#SERVER_NAME##ELEMENT_URL#</a>",
		));
	}

	function UnInstallCEvent() {
		$emessage = new CEventMessage;
		$rsMess = CEventMessage::GetList($by="site_id", $order="desc", array('TYPE' => 'ADD_COMMENT'));
		while ($types = $rsMess->Fetch())
			$emessage->Delete($types['ID']);
		
		$et = new CEventType;
		$et->Delete("ADD_COMMENT");
	}

	function DoInstall() {
		global $APPLICATION;

		$this->InstallDB();
		$this->InstallFiles();
		$this->InstallCEvent();
		$GLOBALS["errors"] = $this->errors;
		$APPLICATION->IncludeAdminFile(GetMessage("INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/install/step.php");
	}

	function DoUninstall() {
	    global $APPLICATION;

	    $this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallCEvent();
		$GLOBALS["errors"] = $this->errors;
	    $APPLICATION->IncludeAdminFile(GetMessage("UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kontora.comments/install/unstep.php");
	}

	function GetModuleRightList() {
		return array(
			"reference_id" => array("D", "K", "N", "R", "W"),
			"reference" => array(
				"[D] ".GetMessage("RIGTHS_D"),
				"[K] ".GetMessage("RIGTHS_K"),
				"[N] ".GetMessage("RIGTHS_N"),
				"[R] ".GetMessage("RIGTHS_R"),
				"[W] ".GetMessage("RIGTHS_W")
			)
		);
	}
}