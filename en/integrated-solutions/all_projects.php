<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("All projects");
?>
    <div class="inner no-full">
        <h1><? $APPLICATION->ShowTitle(false) ?></h1>
        <? $section_ids = BXHelper::getSections(array('ID' => 'ASC'), array('IBLOCK_ID' => '7'), false, array('ID'), true, 'ID'); ?>
        <? $section_ids = array_keys($section_ids['RESULT']); ?>
        <? $choosen_section = !empty($_REQUEST['section']) && in_array($_REQUEST['section'], $section_ids) ? $_REQUEST['section'] : $section_ids ?>
        <? $APPLICATION->IncludeComponent("kontora:element.list", "all-projects", array(
            'IBLOCK_ID' => 37,
            'PROPS' => 'Y',
            "SEF_MODE" => "Y",
            "ORDER" => array('sort' => 'asc'),
            "FILTER" => array('PROPERTY_solution_id' => $choosen_section),
            "SEF_FOLDER" => "/en/integrated-solutions/",
        )); ?>
    </div>
    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="categories inner js-paralax-item">
            <div class="padding">
                <?
                $APPLICATION->IncludeComponent("kontora:section.list", "sections-list", array(
                    'IBLOCK_ID' => 35,
                    'INCLUDE_INACTIVE' => 'Y',
                    'PROPS' => 'Y',
                    "SEF_MODE" => "Y",
                    "SEF_FOLDER" => "/en/integrated-solutions/",
                    "FILTER" => array()
                ));
                ?>
            </div>
        </div>
    </aside>
<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>