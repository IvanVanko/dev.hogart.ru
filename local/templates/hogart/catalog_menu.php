<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 16/05/2017
 * Time: 13:54
 */

$catalogMenu = $APPLICATION->IncludeComponent(
    "bitrix:menu.sections",
    "",
    array(
        "IS_SEF" => "Y",
        "SEF_BASE_URL" => "/catalog/",
        "SECTION_PAGE_URL" => "?SECTION_ID=#SECTION_ID#",
        "DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_ID#/",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "1",
        "DEPTH_LEVEL" => "1",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "3600",
        "INCLUDE_SUBSECTIONS" => "Y"
    ),
    false
);

?>

<ul class="b-catalog-main__list">
    <? foreach ($catalogMenu as $catalogItem): ?>
        <li class="b-catalog-main__item <?= ((string)$_REQUEST['SECTION_ID'] == $catalogItem[3]['SECTION_ID'] ? 'active' : '') ?>">
            <a href="<?= ((string)$_REQUEST['SECTION_ID'] == $catalogItem[3]['SECTION_ID'] ? 'javascript:void(0)' : $catalogItem[1]) ?>" class="b-catalog-main__link" title="<?= $catalogItem[0] ?>">
                <? if (!empty($catalogItem[3]['ICON'])): ?>
                    <? $file = CFile::ResizeImageGet($catalogItem[3]['ICON'], array('width' => 300, 'height' => 300), BX_RESIZE_IMAGE_EXACT, true); ?>
                    <img src="<?= $file['src']; ?>" alt="<?= $catalogItem[0] ?>">
                <? endif; ?>
                <span><?= $catalogItem[0] ?></span>
            </a>
        </li>
    <? endforeach; ?>
</ul>
