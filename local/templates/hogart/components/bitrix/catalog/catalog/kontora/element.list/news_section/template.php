<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (count($arResult['ITEMS']) > 0):?>
	<div class="sidebar_padding_cnt">
		<?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<?$date = explode('.', $arItem['ACTIVE_FROM']);?>
			<?$date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"]));?>
			<div class="date new-date">
<!--                <sup>--><?//=$date[0]?><!--</sup> <span>/</span><sub>--><?//=$date[1]?><!--</sub>-->
				<div><?=$date_from?></div>
            </div>
            <div class="small_news">
                <p><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></p>
            </div>
		<?endforeach;?>
        <a class="more-detail" href="/company/news/">Все новости</a>
	</div>
<?endif; ?>