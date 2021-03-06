<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<h1><?=$arResult['SECTION']['NAME']?></h1>
<ul class="category_list">
<!--    <pre>--><?// var_dump($arResult);?><!--</pre>-->
	<?$depth_level = 0;
    $strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
    $strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
    $arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
	foreach ($arResult['SECTIONS'] as &$arSection):
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
                $file = CFile::ResizeImageGet($arSection['PICTURE']['ID'], array('width'=>101, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                ?>

        <?if ($arSection['RELATIVE_DEPTH_LEVEL'] == 1):?>
			<?if ($depth_level == 2):?></ul><?endif?>
			<?if ($depth_level == 1):?></li><?endif?>
			<li>
                <img src="<?=(!empty($file['src']))? $file['src'] : '/images/no-img.jpg'?>" alt=""/>
                <h2 id="<?=$this->GetEditAreaId($arSection['ID']);?>"><a href="<?=$arSection["SECTION_PAGE_URL"]; ?>"><?=$arSection['NAME']?></a></h2>
<!--                <h2 id="--><?//=$this->GetEditAreaId($arSection['ID']);?><!--">--><?//=$arSection['NAME']?><!--</h2>-->
		<?elseif ($arSection['RELATIVE_DEPTH_LEVEL'] == 2):?>
			<?if ($depth_level == 1):?><ul><?endif?>
			<li id="<?=$this->GetEditAreaId($arSection['ID']);?>"><a href="<?=$arSection["SECTION_PAGE_URL"]; ?>"><?=$arSection["NAME"];?> (<?=$arSection["ELEMENT_CNT"];?>)</a></li>
		<?endif;?>
	<?$depth_level = $arSection['RELATIVE_DEPTH_LEVEL'];
	endforeach;?>
</ul>
</ul>

<?=$arResult['SECTION']['DESCRIPTION']?>