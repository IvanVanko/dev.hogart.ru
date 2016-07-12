<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (!empty($arResult['ITEMS'])):?>
	<?if ($APPLICATION->GetCurPage() != '/learn/comments.php'):?>
		<div class="clearfix">
	        <h4 class="display-inline-block">Отзывы</h4>

	        <div class="control control-action null-margin big text-right">
	            <a class="empty-btn black to-otziv" href="#add-new-comment"><i class="icon-comment"></i>Оставить отзыв</a>
	        </div>
	    </div>
	<?endif?>

	<ul class="comment-learn">
		<?foreach ($arResult['ITEMS'] as $arItem):?>
			<li>	
				<div class="comment<? if ($USER->GetID() == $arItem['CREATED_BY']): ?> mycomment<? endif ?>">
					<!-- div class="user"> <?=$arItem['NAME']?> <span>/ <?=$arItem['PROPERTIES']['company']['VALUE']?> </span><span>/ <?=$arItem['PROPERTIES']['post']['VALUE']?></span></div>
					<div class="comment_text">
						<h1><i class="fa fa-quote-right" aria-hidden="true"></i></h1>
						<?=$arItem['PREVIEW_TEXT']?></div -->

					<blockquote>
						<p>
							<?=$arItem['PREVIEW_TEXT']?>
						</p>
						<footer class="blockquote">
							<?=$arItem['NAME']?> <span>/ <?=$arItem['PROPERTIES']['company']['VALUE']?> </span><span>/ <?=$arItem['PROPERTIES']['post']['VALUE']?></span>
						</footer>
					</blockquote>
				</div>
			</li>
		<?endforeach;?>
	</ul>

	<?if ($APPLICATION->GetCurPage() != '/learn/comments.php' && $arResult['ITEMS_COUNT'] > $arParams['ELEMENT_COUNT']):?>
		<a class="all-comments" href="/learn/comments.php?CID=<?=$arParams['FILTER']['PROPERTY_seminar_id']?>">Все отзывы</a>
	<?endif;?>
<?endif;?>