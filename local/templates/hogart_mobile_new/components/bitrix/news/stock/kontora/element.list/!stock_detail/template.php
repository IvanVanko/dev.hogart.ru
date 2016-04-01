<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (count($arResult["ITEMS"]) > 0):?>
	<div class="sidebar_padding_cnt small-news-cnt">
		<?foreach ($arResult["ITEMS"] as $arItem): 
			$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<?$date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"]));
			$date_to = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_TO"]));?>

			<div class="date_small">
                <?=$date_from?> – <?=$date_to?>
            </div>
            <div class="small_news">
                <p><a href="" title="<?=$arItem['NAME']?>"><?=$arItem['NAME']?></a></p>
            </div>
		<?endforeach;?>
	</div>
<?endif; ?>