<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (count($arResult["ITEMS"]) > 0): ?>
    <div class="stock-preview">
        <? foreach ($arResult["ITEMS"] as $arItem):
            $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
            <? $date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"]));
            $date_to = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_TO"])); ?>

            <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="stock-preview-item">
                <div class="date_small">
                    <?= $date_from ?> – <?= $date_to ?>
                </div>
                <?
                global $USER;
                if (!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y'): ?>
                    <p><a class="profile-url js-popup-open" href="javascript:"
                          data-popup="#popup-login"><?= $arItem['NAME'] ?></a></p>
                    <p>Авторизуйтесь, чтобы узнать акцию</p>
                <? else: ?>
                    <p class="small_news"><?= $arItem['NAME'] ?></p>
                <? endif; ?>
            </a>
        <? endforeach; ?>
    </div>
<? endif; ?>