<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="padding">
            <a class="side-back" href="/company/news/">
                Ко всем новостям
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
                                <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                            </p>
                        </li>
        			<?endforeach;?>
        		</ul>
        	<?endif; ?>
        </div>
    </div>
</aside>