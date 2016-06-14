<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <? $depth_level = 1; ?>
    <ul class="<?= $arParams["CLASS"] ?>" data-depth="<?= $depth_level ?>">
        <? foreach($arResult as $arItem):

        switch (min(2, floor($depth_level / $arItem["DEPTH_LEVEL"]))):
            case 0:
                if ($depth_level == 0) {
                    $str = '<li class="item" data-depth="' . $arItem["DEPTH_LEVEL"] . '">';
                } else {
                    $str = '<ul data-depth="' . $arItem["DEPTH_LEVEL"] . '"><li class="item" data-depth="' . $arItem["DEPTH_LEVEL"] . '">';
                }
                break;
            case 1:
                $str = '</li><li class="item" data-depth="' . $arItem["DEPTH_LEVEL"] . '">';
                break;
            case 2:
                $str = '</li></ul></li><li class="item" data-depth="' . $arItem["DEPTH_LEVEL"] . '">';
                break;
        endswitch;
        ?>
            <?= $str ?>
            <a <?= ($arItem["SELECTED"] == 'true') ? 'class="selected"' : '' ?>
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
