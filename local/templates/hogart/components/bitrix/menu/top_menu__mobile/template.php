<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
global $USER, $APPLICATION;
?>

<ul id="accordion-hamburger" class="main-navigation main-navigation--hamburger panel-group" role="tablist" aria-multiselectable="false">
    <li class="panel catalog-mobile__column">
        <a href="/" title="Главная">
            <div class="image">
                <i class="fa fa-home" aria-hidden="true"></i>
            </div>
            <span>Главная</span>
        </a>
    </li>
<? if (!empty($arResult)): ?>
    <? $depth_level = 1; ?>
    <? foreach($arResult as $index => $arItem):
        $ext = '';
        switch (min(2, floor($depth_level / $arItem["DEPTH_LEVEL"]))):
            case 0:
                if ($depth_level == 0) {
                    $str = '<li>';
                } else {
                    $str = '<ul id="hamburger-' . $index . '" class="navigation-sub-menu collapse ' . ($arItem["SELECTED"] ? 'in' : '') .' panel-collapse" role="tabpanel"><li class="panel catalog-mobile__column">';
                }
                break;
            case 1:
                $str = '</li><li class="panel catalog-mobile__column">';
                break;
            case 2:
                $str = '</li></ul></li><li class="panel catalog-mobile__column">';
                break;
        endswitch;
        ?>

        <? if (isset($arResult[$index + 1]) && min(2, floor($arItem["DEPTH_LEVEL"] / $arResult[$index + 1]["DEPTH_LEVEL"])) == 0 && $arItem["DEPTH_LEVEL"] !== 0): ?>
            <? $arItem["LINK"] = '#hamburger-' . ($index + 1); ?>
            <? $ext = 'data-toggle="collapse" aria-expanded="' . ($arItem["SELECTED"] ? 'true' : 'false') . '" data-parent="#accordion-hamburger" aria-controls="hamburger-' . ($index + 1) . '"'; ?>
        <? endif; ?>
        <?= $str ?>
        <a class="<?= ($arItem["SELECTED"] == 'true') ? ' selected' : '' ?>"
           <?= $ext ?>
           title="<?= $arItem["TEXT"] ?>"
           href="<?= $arItem["LINK"] ?>">

            <? if (!empty($arItem['PARAMS']['mobile_menu_icon'])): ?>
                <div class="image">
                    <img src="<?= $arItem['PARAMS']['mobile_menu_icon'] ?>" />
                </div>
            <? endif; ?>

            <? if (!empty($arItem['PARAMS']['fa_mobile_menu_icon'])): ?>
                <div class="image">
                    <i class="<?= $arItem['PARAMS']['fa_mobile_menu_icon'] ?>" aria-hidden="true"></i>
                </div>
            <? endif; ?>
            <span><?= $arItem["TEXT"] ?></span>
        </a>
        <? $depth_level = $arItem["DEPTH_LEVEL"]; ?>
    <? endforeach;?>
    <? if ($depth_level == 1): ?>
        </li>
    <? else: ?>
        </li></ul></li>
    <? endif; ?>
<? endif; ?>
    <li class="panel catalog-mobile__column">
        <? if ($USER->IsAuthorized()): ?>
            <a class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-hamburger" href="#gamburger-lk" aria-expanded="false" title="Личный кабинет">
                <div class="image">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                </div>
                <span>Личный кабинет</span>
            </a>
            <? $APPLICATION->IncludeComponent("hogart.lk:account.menu", "mobile", []) ?>
        <? else: ?>
            <a href="/account/" title="Личный кабинет">
                <div class="image">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                </div>
                <span>Личный кабинет</span>
            </a>
        <? endif; ?>
    </li>
</ul>

