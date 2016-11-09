<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 24/08/16
 * Time: 14:51
 */
$module_id = "hogart.main";

global $APPLICATION;
$MODULE_RIGHT = $APPLICATION->GetGroupRight($module_id);
if (!($MODULE_RIGHT >= "R")){
    $APPLICATION->AuthForm("ACCESS_DENIED");
}

CModule::IncludeModule($module_id);
/**
 * @var $hogart_main_default_option array
 */
include __DIR__ . "/default_option.php";

/** @noinspection PhpUndefinedVariableInspection */
if($REQUEST_METHOD == "POST" && check_bitrix_sessid()) {
    foreach (array_keys($hogart_main_default_option) as $option) {
        switch (gettype($hogart_main_default_option[$option])) {
            case 'string':
                $method = "SetOptionString";
                break;
            case 'integer':
                $method = "SetOptionInt";
                break;
        }
        COption::$method($module_id, $option, $_POST[$option]);
    }
}
?>

<form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
    <table cellpadding="3" cellspacing="0" border="0" width="0%">
        <tr>
            <td><p><label for="gtm_on">Включить Google Tag Manager</label></p></td>
            <? $gtm_on_checked = COption::GetOptionString($module_id, "GTM_ON") == "Y" ? "checked" : "" ?>
            <td><input type="checkbox" name="GTM_ON" id="gtm_on" value="Y" <?= $gtm_on_checked ?>></td>
        </tr>
        <tr data-checkbox-depends="gtm_on" style="display: none">
            <td><p><label for="gtm_tracking_code">Код Google Tag Manager</label></p></td>
            <td><input type="text" name="GTM_TRACKING_CODE" id="gtm_tracking_code" value="<?= COption::GetOptionString($module_id, "GTM_TRACKING_CODE")?>"></td>
        </tr>
    </table>
    <input type="submit" name="apply" value="Применить">
    <?=bitrix_sessid_post();?>
</form>
<script>
    $(function () {
        $('[data-checkbox-depends]').each(function (i, tr) {
            var selector = '#' + $(tr).attr('data-checkbox-depends');
            $(document).on("change", selector, function () {
                if ($(this).is(":checked")) {
                    $(tr).show();
                } else {
                    $(tr).hide();
                }
            });
            $(selector).change();
        });
    });
</script>