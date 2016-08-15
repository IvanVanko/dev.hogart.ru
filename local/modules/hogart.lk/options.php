<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 15/08/16
 * Time: 15:39
 */
$module_id = "hogart.lk";

global $APPLICATION;
$MODULE_RIGHT = $APPLICATION->GetGroupRight($module_id);
if (!($MODULE_RIGHT >= "R")){
    $APPLICATION->AuthForm("ACCESS_DENIED");
}

//$cache = Bitrix\Main\Application::getInstance()->getManagedCache();
//$cache->clean("b_option");

CModule::IncludeModule($module_id);
$upgradeManager = new \Hogart\Lk\Upgrade\UpgradeManager(true);

if ($_SERVER['REQUEST_METHOD'] == "POST" && check_bitrix_sessid()){

    if (!empty($_REQUEST["upgrade_reload"])){
        $upgradeManager->upgradeReload();
    }
}

?>

<p>Текущая версия: <?= $upgradeManager->getUpgradeVersion() ?></p>

<?// if($upgradeManager->isUpgradeNeeded()): ?>
<form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
    <p><input type="submit" name="upgrade_reload" value="Обновить"></p>
    <?=bitrix_sessid_post();?>
</form>
<?// endif; ?>