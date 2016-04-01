<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>
<!--  <?var_dump($arResult["SEMINARS"])?>  -->
<!--  <?//var_dump($arResult)?>  -->
<?if (count($arResult['ITEMS']) > 0):?>

    <div class="side-datepicker-cnt">
        <ul class="js-dateArray" id="side_news_array">
			<?foreach ($arResult["ITEMS"] as $arItem):
				$date = ConvertDateTime($arItem['ACTIVE_FROM'], "MM/DD/YYYY", "ru");?>

                <li data-date="<?=$date?>">
                    <span class="type_block"><?=$arItem['IBLOCK_NAME'];?></span>
                        <?//=$arItem['NAME']?>
                    <a href="<?=$arItem['DETAIL_PAGE_URL'];?>">
                        <h3><?=$arItem['NAME'];?></h3>
                    </a>
                </li>
			<?endforeach;?>
            <?foreach($arResult["SEMINARS"] as $sem):?>
                <?$date_from = FormatDate("m/d/Y", MakeTimeStamp($sem['PROPERTY_SEM_START_DATE_VALUE']));?>
                <li data-date="<?=$date_from?>">
                    <span class="type_block"><?=$sem['IBLOCK_NAME'];?></span>
                    <?//=$arItem['NAME']?>
                    <a href="<?=$sem['DETAIL_PAGE_URL'];?>">
                        <h3><?=$sem['NAME'];?></h3>
                    </a>
                </li>
            <?endforeach;?>
		</ul>
		<h1>События</h1>
		<div data-datepicker="#side_news_array" class="js-datepicker"></div>
	</div>
<?endif; ?>