<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="padding">
    <a class="side-back" href="<?= SITE_DIR ?>company/news/">
        <?= GetMessage("Ко всем новостям") ?>
        <i class="icon-white-back"></i>
    </a>
</div>
<div class="sidebar_padding_cnt padding">
    <?if (count($arResult['ITEMS']) > 0):?>
        <ul class="news-aside">
            <?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>

                <?$date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"]));?>
                <li>
                    <div class="date">
                        <sub><?=$date_from?></sub>
                    </div>
                    <p>
                        <?
                        global $USER;
                        if(!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y') {
                        ?>
                        <a class="profile-url js-popup-open" href="javascript:" data-popup="#popup-login">
                            <?=$arItem['NAME']?>
                        </a>
                    <p>Для прочтения необходима авторизация на сайте</p>
                    <?
                    }
                    else { ?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                    <? } ?>
                    </p>
                </li>
            <?endforeach;?>
        </ul>
    <?endif; ?>
</div>
