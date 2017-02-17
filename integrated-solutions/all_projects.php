<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Все проекты"); 
?>
<div class="row">
    <div class="col-md-9 col-xs-12">
        <h3><? $APPLICATION->ShowTitle(false) ?></h3>
        <? $section_ids = BXHelper::getSections(array('ID' => 'ASC'), array('IBLOCK_ID' => '7'), false, array('ID'), true, 'ID'); ?>
        <? $section_ids = array_keys($section_ids['RESULT']); ?>
        <? $choosen_section = !empty($_REQUEST['section']) && in_array($_REQUEST['section'], $section_ids) ? $_REQUEST['section'] : $section_ids ?>
        <? $APPLICATION->IncludeComponent("kontora:element.list", "all-projects", array(
            'IBLOCK_ID' => '18',
            'PROPS' => 'Y',
            "SEF_MODE" => "Y",
            "ORDER" => array('sort' => 'asc'),
            "FILTER" => array('PROPERTY_solution_id' => $choosen_section),
        )); ?>
    </div>
    <div class="col-md-3 col-xs-12 aside aside-mobile">
        <div class="filter-stock">
            <a class="filter-stock__link js-filter-stock-mobile" href="#" title=""></a>
            <?
            $APPLICATION->IncludeComponent("kontora:section.list", "sections-list", array(
                'IBLOCK_ID' => '7',
                'INCLUDE_INACTIVE' => 'Y',
                'PROPS' => 'Y',
                "SEF_MODE" => "Y",
                "SEF_FOLDER" => "/integrated-solutions/",
                "FILTER" => array(),
                "CACHE_TIME" => 0
            ));
            ?>
        </div>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>