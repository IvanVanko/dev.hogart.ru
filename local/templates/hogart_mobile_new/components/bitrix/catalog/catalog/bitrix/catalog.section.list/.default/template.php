<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?if(count($arResult['SECTIONS']) > 0):?>
<ul class="inner_menu big-menu">
	<?foreach ($arResult['SECTIONS'] as $key => $arSection):?>
		<?if ($arSection["DEPTH_LEVEL"] == 1) :
		$url = $arSection["SECTION_PAGE_URL"]?>
		<li><a href="<?=$url?>"><?=$arSection['NAME']?></a></li>
		<?endif;?>
	<?endforeach;?>
</ul>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>
<?endif;?>