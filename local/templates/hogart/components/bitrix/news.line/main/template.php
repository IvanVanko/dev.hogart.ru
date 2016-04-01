<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);?>
<?if (!empty($arResult['ITEMS'])):?>
	<section class="side-news-cnt azazaz">
	    <h1>Новости</h1>

	    <ul class="side-news-list">
			<?foreach($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				
				$date = explode('/', $arItem['DISPLAY_ACTIVE_FROM']);?>

				<li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		            <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
		           		<div class="date">
			                <sup><?=$date[0]?></sup>
			                <sub><?=$date[1]?></sub>
			            </div>
			            <p><?=$arItem['NAME']?></p>
		            </a>
		        </li>
			<?endforeach;?>
		</ul>
		
		<?if (count($arResult['ITEMS']) > 1):?>
			<div class="control">
		        <span class="prev"></span><span class="next"></span>
		    </div>
		<?endif;?>

	</section>
<?endif;?>