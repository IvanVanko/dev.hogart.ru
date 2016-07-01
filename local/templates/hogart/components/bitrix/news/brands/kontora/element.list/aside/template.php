<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (count($arResult["ITEMS"]) > 0):?>
    <div class="brand-links">
        <div class="row">
            <? foreach ($arResult["ITEMS"] as $arItem): ?>
                <div class="col-md-6">
                    <a href="<?= $arItem["DETAIL_PAGE_URL"]?>">
                        <?= $arItem["NAME"] ?>
                    </a>
                </div>
            <? endforeach; ?>
        </div>
    </div>
<? endif; ?>