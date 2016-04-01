<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (!empty($arResult)):
	$depth_level = 0;?>
	<ul class="presentation-main-page js-fh">
		<?foreach ($arResult as $arItem):
			if ($depth_level == 1 && $arItem["DEPTH_LEVEL"] == 1)
				$str = '</h1><hr></div></li><li><img src="'.$arItem['PARAMS']['picture'].'" alt="Hogart"/><div class="inner"><h1>';
		    elseif ($depth_level == 1 && $arItem["DEPTH_LEVEL"] == 2)
		    	$str = '</h1><ul><li>';
		    elseif ($depth_level == 2 && $arItem["DEPTH_LEVEL"] == 2)
		    	$str = '</li><li>';
		    elseif ($depth_level == 2 && $arItem["DEPTH_LEVEL"] == 1)
		    	$str = '</li></ul><hr></div></li><li><img src="'.$arItem['PARAMS']['picture'].'" alt="Hogart"/><div class="inner"><h1>';
			elseif ($depth_level == 0 && $arItem["DEPTH_LEVEL"] == 1)
				$str = '<li><img src="'.$arItem['PARAMS']['picture'].'" alt="Hogart"/><div class="inner"><h1>';?>

		    <?=$str?><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
			
			<?$depth_level = $arItem["DEPTH_LEVEL"];
		endforeach;?>
		
		<?if ($depth_level == 1)
			echo '</h1><hr></div></li>';
		else
			echo '</li></ul><hr></div></li>';?>
	</ul>
<?endif?>