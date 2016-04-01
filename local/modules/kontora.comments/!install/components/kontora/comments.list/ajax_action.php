<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $CACHE_MANAGER;

if (isset($_REQUEST['voting'])) {
	$CACHE_MANAGER->ClearByTag("kontora_comments_" . $_REQUEST['voting']);
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>