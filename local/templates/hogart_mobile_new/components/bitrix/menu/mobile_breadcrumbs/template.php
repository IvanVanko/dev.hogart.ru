<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$cnt = 0;
$total = count($arResult);
?>
<?if (count($arResult) > 0):?>
<aside class="breadcrumbs">
	<div class="prev-pages-wrap">
	<?foreach($arResult as $k=> $arItem):?>
		<?if ($cnt == $total -1) { $_arItem = $arItem; break;}?>
		<a class="page" href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>"><?=$arItem["TEXT"]?></a>
		<?$cnt++;?>
	<?endforeach;?>
	</div>
	<a class="page active" href="<?=$_arItem["LINK"]?>" title="<?=$_arItem["TEXT"]?>"><?=$_arItem["TEXT"]?></a>
</aside>
<?endif?>