<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);?>
<?foreach ($arResult["ITEMS"] as $arItem):?>
	<?#DebugMessage($arItem);?>
	<article class="news-one">
		<h2><a href="<?= $arItem["DETAIL_PAGE_URL"]?>"><?= $arItem["NAME"]?></a></h2>
		<p><?=$arItem["PREVIEW_TEXT"]?></p>
		<? $date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"])); ?>
		<div class="article-info"> <time datetime="<?=$date_from?>"><?=$date_from?> </time> 
			<?if (count($arItem["PROPERTIES"]["tag"]["VALUE"]) > 0 ):?>
				<? foreach ($arItem["PROPERTIES"]["tag"]["VALUE"] as $key => $tag): ?>
					<?
					$page = $APPLICATION->GetCurPageParam("tag[" . $arItem['PROPERTIES']['tag']['VALUE_ENUM_ID'][$key] . "]=" . $tag, array("tag","ajax", "PAGEN_1","filter"));
					$page = str_replace("news_list.php","index.php",$page);
					?>
					<a href="<?= $page ?>"> <?= $tag ?></a><br />
				<? endforeach; ?>
			<?endif;?>
		</div>
	</article>
<?endforeach;?>
<?
#DebugMessage($arResult['ITEMS']);
?>