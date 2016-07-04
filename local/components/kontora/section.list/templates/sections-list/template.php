<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
?>

<div class="sections-list">
    <a href="<?= SITE_DIR ?>integrated-solutions/all_projects.php"<? if (!isset($_REQUEST['section']) || empty($_REQUEST['section'])): ?> class="selected"<? endif; ?>><?= GetMessage("Все") ?></a>
    <? if (count($arResult['SECTIONS']) > 0): ?>
        <? $curPage = $_REQUEST['/integrated-solutions/section_detail_php?section']; ?>
        <? foreach ($arResult["SECTIONS"] as $arSection):
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams); ?>
            <a href="?section=<?= $arSection['ID'] ?>"<? if ($_REQUEST['section'] == $arSection['ID']):?> class="selected"<? endif; ?>><?= $arSection['NAME'] ?></a>
        <? endforeach; ?>
    <? endif; ?>
</div>