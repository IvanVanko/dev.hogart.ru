<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
$component = $this->getComponent();
$this->setFrameMode(true);

use Hogart\Lk\Helper\Template\Ajax;

?>

<nav aria-label="Page navigation">
    <ul class="pagination">
        <? if ($arResult["REVERSED_PAGES"] === true): ?>

            <? if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]): ?>
                <? if (($arResult["CURRENT_PAGE"] + 1) == $arResult["PAGE_COUNT"]): ?>
                    <li>
                        <a <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($arResult["URL"]), $arParams['AJAX_CONTAINER']) : ""?> href="<?= htmlspecialcharsbx($arResult["URL"]) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <? else: ?>
                    <li>
                        <a <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)), $arParams['AJAX_CONTAINER']) : ""?> href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)) ?>"
                           aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <? endif ?>
                <li><a <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($arResult["URL"]), $arParams['AJAX_CONTAINER']) : ""?> href="<?= htmlspecialcharsbx($arResult["URL"]) ?>">1</a></li>
            <? else: ?>
                <li>
                    <a href="javascript:void(0)"><span aria-hidden="true">&laquo;</span></a>
                </li>
                <li><a href="javascript:void(0)" class="active">1</a></li>
            <? endif ?>

            <?
            $page = $arResult["START_PAGE"] - 1;
            while ($page >= $arResult["END_PAGE"] + 1):
                ?>
                <? if ($page == $arResult["CURRENT_PAGE"]):?>
                <li><a href="javascript:void(0)" class="active"><?= ($arResult["PAGE_COUNT"] - $page + 1) ?></a></li>
            <? else:?>
                <li class=""><a
                        <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($component->replaceUrlTemplate($page)), $arParams['AJAX_CONTAINER']) : ""?>
                        href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($page)) ?>"><?= ($arResult["PAGE_COUNT"] - $page + 1) ?></a>
                </li>
            <? endif ?>

                <? $page-- ?>
            <? endwhile ?>

            <? if ($arResult["CURRENT_PAGE"] > 1): ?>
                <? if ($arResult["PAGE_COUNT"] > 1): ?>
                    <li class=""><a
                            <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($component->replaceUrlTemplate(1)), $arParams['AJAX_CONTAINER']) : ""?>
                            href="<?= htmlspecialcharsbx($component->replaceUrlTemplate(1)) ?>"><?= $arResult["PAGE_COUNT"] ?></a>
                    </li>
                <? endif ?>
                <li>
                    <a
                        <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1)), $arParams['AJAX_CONTAINER']) : ""?>
                        href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1)) ?>"
                       aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <? else: ?>
                <? if ($arResult["PAGE_COUNT"] > 1): ?>
                    <li><a href="javascript:void(0)" class="active"><?= $arResult["PAGE_COUNT"] ?></a></li>
                <? endif ?>
                <li>
                    <a href="javascript:void(0)"><span aria-hidden="true">&raquo;</span></a>
                </li>
            <? endif ?>

        <? else: ?>

            <? if ($arResult["CURRENT_PAGE"] > 1): ?>
                <? if ($arResult["CURRENT_PAGE"] > 2): ?>
                    <li>
                        <a
                            <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1)), $arParams['AJAX_CONTAINER']) : ""?>
                            href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1)) ?>"
                           aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <? else: ?>
                    <li>
                        <a
                            <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($arResult["URL"]), $arParams['AJAX_CONTAINER']) : ""?>
                            href="<?= htmlspecialcharsbx($arResult["URL"]) ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <? endif ?>
                <li class=""><a
                        <?= Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($arResult["URL"]), $arParams['AJAX_CONTAINER'])?>
                        href="<?= htmlspecialcharsbx($arResult["URL"]) ?>">1</a></li>
            <? else: ?>
                <li>
                    <a href="javascript:void(0)"><span aria-hidden="true">&laquo;</span></a>
                </li>
                <li><a href="javascript:void(0)" class="active">1</a></li>
            <? endif ?>

            <?
            $page = $arResult["START_PAGE"] + 1;
            while ($page <= $arResult["END_PAGE"] - 1):
                ?>
            <? if ($page == $arResult["CURRENT_PAGE"]):?>
                <li><a href="javascript:void(0)" class="active"><?= $page ?></a></li>
            <? else:?>
                <li class=""><a
                        <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($component->replaceUrlTemplate($page)), $arParams['AJAX_CONTAINER']) : ""?>
                        href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($page)) ?>"><?= $page ?></a></li>
            <? endif ?>
                <? $page++ ?>
            <? endwhile ?>

            <? if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]): ?>
                <? if ($arResult["PAGE_COUNT"] > 1): ?>
                    <li class=""><a
                            <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"])), $arParams['AJAX_CONTAINER']) : ""?>
                            href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"])) ?>"><?= $arResult["PAGE_COUNT"] ?></a>
                    </li>
                <? endif ?>
                <li>
                    <a
                        <?= $arParams['AJAX_NODE'] ? Ajax::OnClickUrl($arParams['AJAX_NODE']->getId(), htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)), $arParams['AJAX_CONTAINER']) : ""?>
                        href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)) ?>"
                       aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <? else: ?>
                <? if ($arResult["PAGE_COUNT"] > 1): ?>
                    <li><a href="javascript:void(0)" class="active"><?= $arResult["PAGE_COUNT"] ?></a></li>
                <? endif ?>
                <li>
                    <a href="javascript:void(0)"><span aria-hidden="true">&raquo;</span></a>
                </li>
            <? endif ?>
        <? endif ?>
    </ul>
</nav>
