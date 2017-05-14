<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
global $USER;
?>

<? if (!empty($arResult['ITEMS'])): ?>
    <div class="b-news-main">
        <h2>
            <a href="<?= $arParams['LINK']?>" class="b-title-link" title="<?= $arParams['TITLE_NAME']?>"><?= $arParams['TITLE_NAME']?></a>
        </h2>

        <ul class="b-news-main__list">
            <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                $date = explode('.', $arItem['ACTIVE_FROM']);
                $date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"]));
                ?>

                <li id="<?= $this->GetEditAreaId($arItem['ID']); ?>" class="b-news-main__item">
                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" title="" class="b-news-main__link">
                        <? if (!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?>
                            <img src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>" alt="">
                        <? endif; ?>
                        <span><?= $date_from ?></span>
                        <h3><?= $arItem['NAME'] ?></h3>
                        <p><?= $arItem['PREVIEW_TEXT'] ?></p>
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endif; ?>