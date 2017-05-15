<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
global $USER;
?>

<? if (!empty($arResult['ITEMS'])): ?>
    <div class="b-brands-main">

        <h2>
            <a href="<?= $arParams['LINK']?>" class="b-title-link" title="<?= $arParams['TITLE_NAME']?>"><?= $arParams['TITLE_NAME']?></a>
        </h2>

        <ul class="b-brands-main__list">
            <? foreach ($arResult["ITEMS"] as $key => $arItem): ?>
                <?
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                $date = explode('.', $arItem['ACTIVE_FROM']);
                $date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"]));
                $file = CFile::ResizeImageGet($arItem['PROPERTIES']['INDEX_LOGO']['VALUE'], array('width' => 300, 'height' => 300), BX_RESIZE_IMAGE_EXACT, true);
                ?>
                <li id="<?= $this->GetEditAreaId($arItem['ID']); ?>" class="b-brands-main__item">
                    <a href="javascript:void(0)" class="b-brands-main__link" title="">
                        <img src="<?= $file['src'] ?>" alt="<?= $arItem['NAME'] ?>" title="<?= $arItem['NAME'] ?>" />
                    </a>
                </li>
            <? endforeach; ?>
        </ul>
    </div>

<? endif; ?>