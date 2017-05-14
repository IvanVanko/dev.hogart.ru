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
        <div class="search-container suggest">
            <div class="b-catalog-main__search">
                <input
                        data-suggest-url="<?= $arResult["FORM_ACTION"] ?>"
                        data-suggest-ajax-key="<?= $arResult["AJAX_PARAMS"] ?>"
                        type="text" name="q"
                        value="<?= $_REQUEST['q'] ?>"
                        placeholder="<?= GetMessage('BSF_T_PLACEHOLDER') ?>"
                        class="b-catalog-main__input"
                />
                <a class="b-catalog-main__icon" href="#" title="">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </a>
            </div>
            <button class="btn btn-primary" type="submit" value="Искать">Искать</button>
        </div>
    </form>
</div>