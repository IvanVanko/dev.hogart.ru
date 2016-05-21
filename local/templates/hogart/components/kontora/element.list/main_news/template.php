<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);?>
<?if (!empty($arResult['GROUPS'])):?>
	<section class="side-news-cnt">
	    <h1><a href="<?= SITE_DIR ?>company/news/"><?= GetMessage("Новости") ?></a></h1>
	    <ul class="side-news-list">
            <?foreach ($arResult['GROUPS'] as $arItems) {?>
                <li>
                    <?foreach ($arItems as $key =>  $arItem) {
                        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                        $date = explode('.', $arItem['ACTIVE_FROM']);
                        $date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"]));?>
                        <?
                        global $USER;
                        if(!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y') {
                            ?>
                            <a class="profile-url js-popup-open" href="javascript:" data-popup="#popup-login">
                                <div class="date">
                                    <div><?=$date_from?></div>
                                </div>
                                <p><?=$arItem['NAME']?></p>
                                <p><?= GetMessage("Для прочтения необходима авторизация на сайте") ?></p>
                            </a>
                            <?
                        }
                        else { ?>
                            <a id="<?=$this->GetEditAreaId($arItem['ID']);?>" href="<?=$arItem['DETAIL_PAGE_URL']?>">
                                <div class="date">
                                    <div><?=$date_from?></div>
                                </div>
                                <p><?=$arItem['NAME']?></p>
                            </a>
                        <? } ?>
                    <?}?>
                </li>
            <?}?>
		</ul>
        <?if (count($arResult['ITEMS']) > 2):?>
            <div class="control">
                <span class="prev"></span><span class="next"></span>
            </div>
        <?endif;?>
	</section>
<?endif;?>