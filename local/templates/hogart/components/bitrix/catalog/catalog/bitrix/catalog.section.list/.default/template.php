<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>

<section class="catalog_list_cnt inner no-padding">
    <ul class="catalog_ul">
		<?$depth_level = 0;
		foreach ($arResult['SECTIONS'] as $key => $arSection):
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>
			<?if ($arSection['RELATIVE_DEPTH_LEVEL'] == 1):?>
				<?if ($depth_level == 2):?></ul><?endif?>
				<?if ($depth_level == 1):?></li><?endif?>
				<li class="js-equal-height" data-group="catG">
					<div class="img_wrap" style="background-image: url(<?=$arSection['PICTURE']['SRC']?>);">
<!--	                    <img src="--><?//=$arSection['PICTURE']['SRC']?><!--" alt=""/>-->
	                </div>

                        <h2><a href="<?=$arSection["SECTION_PAGE_URL"]; ?>"><?=$arSection['NAME']?></a></h2>
                        <?if ((int)$arSection["UF_PRICE"]>0) {?>
                        <div class="small_href">
                            <a href="<?=CFile::GetPath($arSection["UF_PRICE"]); ?>" class="doc_view icon_doc" download><?=$arSection["UF_PRICE_LABEL"]?></a>
                        </div>
                        <?}?>
			<?elseif ($arSection['RELATIVE_DEPTH_LEVEL'] == 2):?>
				<?if ($depth_level == 1):?><ul><?endif?>
				<li><a data-href="<?=$arResult['SECTIONS_F'][$key]['SECTION_PAGE_URL']; ?>" href="<?=$arSection["SECTION_PAGE_URL"]; ?>"><?=$arSection["NAME"];?>  (<?=$arSection["ELEMENT_CNT"];?>)</a> </li>
				<li>

                    <?
                   /* $sub_sect_url = array();
                    foreach($arResult['SECTIONS_F'] as $key => $sect_f){

                        if($sect_f['IBLOCK_SECTION_ID']==$arSection['ID']){
                            array_push($sub_sect_url, $sect_f['SECTION_PAGE_URL']);
                        }
                    }*/
                    ?>

<!--                    <a href="--><?//=$sub_sect_url[0]?><!--">--><?//=$arSection["NAME"];?><!--  (--><?//=$arSection["ELEMENT_CNT"];?><!--)</a> </li>-->
			<?endif;?>
		<?$depth_level = $arSection['RELATIVE_DEPTH_LEVEL'];
		endforeach;?>
	</ul>
</section>