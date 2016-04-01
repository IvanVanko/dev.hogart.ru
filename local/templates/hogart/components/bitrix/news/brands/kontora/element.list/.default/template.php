<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (count($arResult["ITEMS"]) > 0):?>
	<ul class="brands-list">

		<?foreach ($arResult["ITEMS"] as $arItem): 
			$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<?$date = explode(".", $arItem["ACTIVE_FROM"]);?>
			<li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
<!--                --><?//=$arItem['PREVIEW_PICTURE']['SRC']?><!-- <br/>-->
<!--                --><?//var_dump($arItem['PREVIEW_PICTURE']) ?>
                <!--<a href="<?/*=$arItem['DETAIL_PAGE_URL']*/?>">
                    <figure>
                        <img class="js-vertical-center" src="<?/*=$arItem['PREVIEW_PICTURE']['SRC']*/?>" alt=""/>
                        <figcaption><?/*=$arItem['NAME']*/?></figcaption>
                    </figure>
                </a>-->
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                    <span>
                        <?$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array("width" => 160, "height" => 90), BX_RESIZE_IMAGE_EXACT);?>
<!--                        <img class="js-vertical-center" src="--><?//=$file['src']?><!--" alt=""/>-->
                        <img class="js-vertical-center" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt=""/>
                    </span>
                    <span><?=$arItem['NAME']?></span>
                </a>
            </li>
		<?endforeach;?>
	</ul>
<?endif; ?>