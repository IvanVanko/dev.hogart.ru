<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
//var_dump($arResult);
if (count($arResult['SECTIONS']) > 0):?>
	<ul class="counter-com">
		<?foreach ($arResult["SECTIONS"] as $arSection):
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);?>
			<li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
				<a href="<?=$arSection["SECTION_PAGE_URL"]?>">
                    <? if (!empty($arSection["PICTURE"])): ?>
                    <span class="complex-icon">
                        <img src="<?=($arSection['PICTURE']['SRC'])?$arSection['PICTURE']['SRC']:'/images/solution-default.png'?>" alt="<?=$arSection['NAME']?>"/>
                    </span>
                    <? endif; ?>
                    <p>
                        <?=$arSection['NAME']?>
                    </p>
                    <?/* if ($arSection["DESCRIPTION"]): ?>
                        <p>
                            <?=$arSection["DESCRIPTION"]?>
                        </p>
                    <? endif;*/?>
<!--                    <span class="complex-icon"><img src="/images/office-icon.png" /></span>-->
                </a>


			</li>
		<?endforeach;?>
	</ul>
<?endif; ?>