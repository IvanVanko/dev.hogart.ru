<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<? $relative_level = 0; ?>
<div class="row d-0"><div class="col-md-12">
		<? foreach ($arResult['SECTIONS'] as $key => $arSection): ?>
		<?
		if (empty($arSection["ELEMENT_CNT"])) continue;
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
		?>
		<? if($relative_level != $arSection["DEPTH_LEVEL"] && $relative_level != 0): ?>
	</div></div><div class="row d-<?= $arSection["DEPTH_LEVEL"] ?>"><div class="col-md-12">
		<? endif; ?>

		<? if ($arSection["DEPTH_LEVEL"] == 1): ?>
			<div class="row depth-1 vertical-align">
				<i id="<?= ("bx_cat_" . $arSection['ID']) ?>"></i>
				<div class="col-md-6">
					<div class="row vertical-align">
						<div class="col-md-1">
							<i class="icon-<?= $arSection["CODE"] ?>"></i>
						</div>
						<div class="col-md-11 text-uppercase title"><a href="<?= $arSection["SECTION_PAGE_URL"] ?>"><?= $arSection["NAME"] ?></a></div>
					</div>
				</div>
				<div class="col-md-6">
					<? if(!empty($arSection["UF_PRICE"])): ?>
						<? $priceFileMeta = CFile::MakeFileArray($arSection["UF_PRICE"]) ?>
						<span class="price-list">
									<a href="<?=CFile::GetPath($arSection["UF_PRICE"]); ?>" class="download">
										<i class="fa fa-download"></i> <span>Скачать <?=$arSection["UF_PRICE_LABEL"]?></span>
									</a>
									<span class="file-metadata">
										<?= strtoupper(explode('/', $priceFileMeta['type'])[1]) ?>, <?= convert($priceFileMeta['size']) ?>
									</span>
								</span>
					<? endif; ?>
				</div>
			</div>
		<? endif; ?>

		<? if ($arSection["DEPTH_LEVEL"] == 2): ?>
			<div class="row depth-2">
				<div class="col-md-6" id="<?= ("bx_cat_" . $arSection['ID']) ?>">
					<div class="row">
						<div class="col-md-offset-1 col-md-11 text-uppercase title"><a href="<?= $arSection["SECTION_PAGE_URL"] ?>"><?= $arSection["NAME"] ?></a></div>
					</div>
				</div>
			</div>
		<? endif; ?>
		<? if ($arSection["DEPTH_LEVEL"] == 3): ?>
			<div class="row depth-3">
				<div class="col-md-6" id="<?= ("bx_cat_" . $arSection['ID']) ?>">
					<div class="row">
						<div class="col-md-offset-1 col-md-11 title"><a href="<?= $arSection["SECTION_PAGE_URL"] ?>"><?= $arSection["NAME"] ?></a></div>
					</div>
				</div>
				<div class="col-md-6">
					<? foreach ($arSection["BRANDS"] as $brand): ?>
						<span class="brand"><a href="<?= $arSection["SECTION_PAGE_URL"] ?>?arrFilter_<?= $arParams["FILTER"]["brand"] ?>_<?= abs(crc32($brand["ID"])) ?>=Y&set_filter=Показать"><?= $brand["NAME"] ?></a></span>
					<? endforeach; ?>
				</div>
			</div>
		<? endif; ?>
		<? $relative_level = $arSection["DEPTH_LEVEL"]; ?>
		<? endforeach; ?>
	</div>
</div>