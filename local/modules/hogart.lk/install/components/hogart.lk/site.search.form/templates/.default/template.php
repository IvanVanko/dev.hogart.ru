<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 11/11/2016
 * Time: 19:55
 */
$this->setFrameMode(true);
?>

<div id="header-search" class="search-cnt">
    <form action="<?= $arResult["FORM_ACTION"] ?>">
        <div class="suggest">
            <input
                data-suggest-url="<?= $arResult["FORM_ACTION"] ?>"
                data-suggest-ajax-key="<?= $arResult["AJAX_PARAMS"] ?>"
                type="text" name="q"
                value="<?= $_REQUEST['q'] ?>"
                placeholder="<?= GetMessage('BSF_T_PLACEHOLDER') ?>"
            />
        </div>
        <button class="icon-search icon-full"></button>
    </form>
</div>