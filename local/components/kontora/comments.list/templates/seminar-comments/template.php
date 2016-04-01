<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//Статусы
define('U_NEW', 1 << 0);  
define('U_AGREE', 1 << 1);
?>

<?$page = $APPLICATION->GetCurPage();?>
<!--<?//var_dump($arResult['COMMENTS'])?>-->
<?//=count($arResult['COMMENTS'])?>
<?
if (count($arResult['COMMENTS']) > 0):
//if (!empty($arResult)):
	$level = 1;
	?>
    <div class="clearfix">
        <h2 class="display-inline-block">Отзывы</h2>

        <div class="control control-action null-margin big text-right">
            <a class="empty-btn black to-otziv" href="#add-new-comment"><i class="icon-comment"></i>Оставить отзыв</a>
        </div>
    </div>
    <ul class="comment-learn">
		<? foreach ($arResult['COMMENTS'] as $arComment):
			if ($arComment['LEVEL'] == $level)
				echo '</li><li>';
			elseif ($arComment['LEVEL'] > $level)
				echo '<ul><li>';
			elseif ($arComment['LEVEL'] < $level) {
				if ($level !== 0) 
					echo str_repeat('</li></ul>', $level - $arComment['LEVEL']);			
				echo '<li>';
			}
			?>
			<div class="comment<? if ($USER->GetID() == $arComment['USER_ID']): ?> mycomment<? endif ?>">
				<div class="comment_text"><?=$arComment['COMMENT']?></div>
                <div class="user"> <?=$arComment['USER_NAME'].' '.$arComment['LAST_NAME']?> <span>/ ООО Симбирск</span></div>
                <?/*
                <div class="author">
                    <? if ($arParams['SHOW_USER_PHOTO'] == 'Y'): ?><img src="<?=$arComment['PERSONAL_PHOTO']?>" /><? endif; ?>
                    <?=$arComment['USER_NAME'].' '.$arComment['LAST_NAME']?> <?=$arComment['DATE_CREATE']?>
                </div>
                */?>
				<?/*
                <div class="buttons">
					<? if ($arResult['RIGHTS'] >= 'N' && $arParams['ONE_LEVEL'] != 'Y'): ?>
						<a href="#" id="<?=$arComment['ID']?>" class="add"><?=GetMessage("ANSWER")?></a>
					<? endif; ?>
					<? if ($arResult['RIGHTS'] >= 'R'): ?>
						<a href="<?=$APPLICATION->GetCurPageParam("delete=".$arComment['ID'], array("delete", "agree", "id", "status"))?>" class="delete"><?=GetMessage("DELETE")?></a>
						<? if ($arComment['STATUS'] & U_AGREE):
							echo GetMessage("AGREE_Y");
						else:?>
							<a href="<?=$APPLICATION->GetCurPageParam("agree=y&id=".$arComment['ID']."status=".$arComment['STATUS'], array("delete", "agree", "id", "status"))?>" id="<?=$arComment['ID']?>" class="agree"><?=GetMessage("AGREE")?></a>
						<?endif;
						if ($arComment['STATUS'] & (U_AGREE | U_NEW)):?>
							<a href="<?=$APPLICATION->GetCurPageParam("agree=n&id=".$arComment['ID']."status=".$arComment['STATUS'], array("delete", "agree", "id", "status"))?>" id="<?=$arComment['ID']?>" class="agree"><?=GetMessage("DISAGREE")?></a>
						<? else:
							echo GetMessage("AGREE_N");
						endif;
					endif;
					if (($USER->GetID() == $arComment['USER_ID'] && $arResult['RIGHTS'] >= 'N') || $arResult['RIGHTS'] >= 'R'):?>
						<a href="#" class="edit" id="<?=$arComment['ID']?>"><?=GetMessage("EDIT")?></a>
					<? endif; ?>
				</div>
                */?>
				<?/* if ($arParams['USE_RATING'] == 'Y') {
					if ($arParams['USE_LIKES'] == 'Y')
						$template = 'like';
					elseif  ($arParams['USE_VOTE'] == 'Y')
						$template = 'standart_text';

					$GLOBALS["APPLICATION"]->IncludeComponent(
					   "bitrix:rating.vote", $template,
					   Array(
							"ENTITY_TYPE_ID" => "COMMENTS",
							"ENTITY_ID"      => $arComment['ID'],		//передаем ID элемента
							"OWNER_ID"       => $arComment['USER_ID'],	//передаем ID автора элемента 
							"CACHE_TYPE"     => "A",
        					"CACHE_TIME"     => "3600"
					   ),
					   false,
					   array("HIDE_ICONS" => "Y")
					);
				}*/?>
			</div>
			<?$level = $arComment['LEVEL'];
		endforeach;
		if ($level == 1)
			echo '</li>';
		else 
			echo str_repeat('</li></ul>', $level);
		?>
	</ul>

    <?if ($page!='/learn/comments.php'):?>
    <a class="all-comments" href="/learn/comments.php?CID=<?=$_REQUEST["CID"]?>">Все отзывы</a>
    <?endif;?>

    <?if ($page!='/learn/comments.php'):?>
    <h2>Оставить отзыв</h2>

    <div id="add-new-comment">
        <?$APPLICATION->IncludeComponent("kontora:comments.addform", "seminar-comment-form", array());?>
    </div>
    <?endif;?>
<?else:?>

    <?if ($page!='/learn/comments.php'):?>
        <h2>Оставьте отзыв первым</h2>
        <div id="add-new-comment">
            <?$APPLICATION->IncludeComponent("kontora:comments.addform", "seminar-comment-form", array());?>
        </div>
    <?endif;?>

<?endif;?>

<?
if ($arResult['USE_WYSIWYG'] == 'Y'):?>
	<input type="hidden" name="wysiwyg" value="y" />
<?endif;?>
<?if(isset($arParams['FILTER']['ELEMENT_ID'])):?>
	<input type="hidden" name="cacheId" value="<?=$arParams['FILTER']['ELEMENT_ID']?>" />
<?elseif(isset($arParams['FILTER']['USER_ID'])):?>
	<input type="hidden" name="cacheId" value="user_<?=$arParams['FILTER']['USER_ID']?>" />
<?endif;?>