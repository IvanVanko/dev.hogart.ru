<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<? global $USER; ?>

<h3 class="title">Другие новости</h3>
<h6 class="more text-right">
    <a href="<?= SITE_DIR ?>company/news/">
        <?= GetMessage("Ко всем новостям") ?>
        <i class="fa fa-angle-double-right" aria-hidden="true"></i>
    </a>
</h6>
<div class="clearfix"></div>
<? if (count($arResult['ITEMS']) > 0): ?>
    <ul class="news-aside">
        <?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>

            <?$date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"]));?>
            <li>
                <a
                    data-popup="<?= (!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y' ? "#popup-login" : "") ?>"
                    class="<?= (!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y' ? "profile-url js-popup-open" : "") ?>"
                    href="<?= (!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y' ? "javascript:void(0);" : $arItem['DETAIL_PAGE_URL']) ?>"
                >
                    <div class="date">
                        <sub><?=$date_from?></sub>
                    </div>
                    <p>
                        <?=$arItem['NAME']?>
                    </p>
                </a>
            </li>
        <?endforeach;?>
    </ul>
<?endif; ?>