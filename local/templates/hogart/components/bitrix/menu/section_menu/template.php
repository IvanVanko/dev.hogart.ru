<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <ul class="text-center left-menu fixed-block" data-rel-fixed-block="#header-block">
        <?
        foreach ($arResult as $arItem):
            if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                continue;
            ?>
            <li>
                <a href="<?= $arItem["LINK"] ?>">
                    <? if(!empty($arItem["PARAMS"]["icon"])): ?>
                        <i class="<?= $arItem["PARAMS"]["icon"] ?>"></i>
                    <? endif; ?>
                    <?= $arItem["TEXT"] ?>
                </a>
            </li>
        <? endforeach ?>
    </ul>
<? endif ?>