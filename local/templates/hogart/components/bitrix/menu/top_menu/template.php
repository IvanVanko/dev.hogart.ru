<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <? $depth_level = 1; ?>
    <ul class="<?= $arParams["CLASS"] ?>" data-depth="<?= $depth_level ?>">
        <? foreach($arResult as $index => $arItem):

        switch (min(2, floor($depth_level / $arItem["DEPTH_LEVEL"]))):
            case 0:
                if ($depth_level == 0) {
                    $str = '<li class="' . $arParams["ITEM_CLASS"] . ' item" data-depth="' . $arItem["DEPTH_LEVEL"] . '">';
                } else {
                    $str = '<ul data-depth="' . $arItem["DEPTH_LEVEL"] . '"><li class="' . $arParams["ITEM_CLASS"] . ' item" data-depth="' . $arItem["DEPTH_LEVEL"] . '">';
                }
                break;
            case 1:
                $str = '</li><li class="' . $arParams["ITEM_CLASS"] . ' item" data-depth="' . $arItem["DEPTH_LEVEL"] . '">';
                break;
            case 2:
                $str = '</li></ul></li><li class="' . $arParams["ITEM_CLASS"] . ' item" data-depth="' . $arItem["DEPTH_LEVEL"] . '">';
                break;
        endswitch;
        ?>

            <? if (isset($arResult[$index + 1]) && min(2, floor($arItem["DEPTH_LEVEL"] / $arResult[$index + 1]["DEPTH_LEVEL"])) == 0 && $arItem["DEPTH_LEVEL"] !== 0): ?>
                <? /* $arItem["LINK"] = 'javascript:void(0)'; */ ?>
                <? $arItem["TEXT"] = '<i class="fa fa-angle-right" aria-hidden="true"></i> ' . $arItem["TEXT"]; ?>
            <? endif; ?>
            <?= $str ?>
            <a class="<?= $arParams["LINK_CLASS"] ?> <?= ($arItem["SELECTED"] == 'true') ? ' selected' : '' ?>"
                href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
            <? $depth_level = $arItem["DEPTH_LEVEL"]; ?>
        <? endforeach;?>
        <? if ($depth_level == 1): ?>
            </li>
        <? else: ?>
            </li></ul></li>
        <? endif; ?>
    </ul>
<? endif; ?>
