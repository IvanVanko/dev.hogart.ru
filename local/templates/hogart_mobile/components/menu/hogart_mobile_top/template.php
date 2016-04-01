<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?if (!empty($arResult)):?>
    <aside class="nav-selecter">
        <ul>
		<?foreach ($arResult as $k => $arItem):?>
            <?$active = $arItem['SELECTED'] ? "active":""?>
            <li class="menu_<?=$k+1?>">
                <a href="#<?=$arItem['PARAMS']['CODE']?>" class="slide-trigger <?=$active?>" <?=$arItem['PARAMS']['DATA']?>>
                    <?=$arItem['PARAMS']['ADDITIONAL_BLOCK']?>
                </a>
            </li>
		<?endforeach;?>
        </ul>
	</aside>
<?endif?>