<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="padding normal-padding">
            <a href="<?=$arParams['SEF_FOLDER']?>" class="side-back">Ко всем статьям <i class="icon-white-back"></i></a>
        </div>
        <div class="sidebar_padding_cnt small-news-cnt">
			<?foreach ($arResult["ITEMS"] as $arItem): 
				$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
				<div class="date_small">
                    <?=CIBlockFormatProperties::DateFormat('j F Y', MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()))?>
                </div>
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="small_header small_header-dop"><?=$arItem['NAME']?></a>

                <div class="small_news small_news-dop">
                    <p><?=$arItem['PREVIEW_TEXT']?></p>
                </div>
			<?endforeach;?>
		</div>
        <div class="action_page">
            <div class="side_href">
                <a href="#" class="icon-email js-popup-open" data-popup="#popup-subscribe"><?= GetMessage("Отправить на e-mail") ?></a>
                <a href="#" onclick="window.print(); return false;" class="icon-print"><?= GetMessage("Распечатать") ?></a>
                <a href="#" class="icon-phone js-popup-open" data-popup="#popup-subscribe-phone"><?= GetMessage("Отправить SMS") ?></a>
            </div>
        </div>
	</div>
</aside>