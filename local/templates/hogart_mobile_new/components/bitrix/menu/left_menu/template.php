<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<!--SELECTED-->
<?if (!empty($arResult)):
	$depth_level = 0;?>
	<div class="hide-block">
	    <input type="checkbox" id="main-menu-trigger">
	    <nav class="hidden-menu">
	        <label class="icon-close icon-full" for="main-menu-trigger"></label>
	        <ul>
				<?foreach($arResult as $arItem):
//					var_dump($arItem);
					if ($depth_level == $arItem["DEPTH_LEVEL"])
						$str = '</li><li>';
				    elseif ($depth_level == 1 && $arItem["DEPTH_LEVEL"] == 2)
				    	$str = '<ul><li>';
				    elseif ($depth_level == 2 && $arItem["DEPTH_LEVEL"] == 1)
				    	$str = '</li></ul></li><li>';
					elseif ($depth_level == 0 && $arItem["DEPTH_LEVEL"] == 1)
						$str = '<li>';?>

				    <?=$str?><a <?=($arItem["SELECTED"]=='true')?'class="selected"':''?> href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>

					<?$depth_level = $arItem["DEPTH_LEVEL"];
				endforeach?>
				<?if ($depth_level == 1)
					echo '</li>';
				else
					echo '</li></ul></li>';?>
			</ul>
        </nav>
    </div>
<?endif?>