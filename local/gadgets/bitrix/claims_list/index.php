<?
if (!isset($_SERVER['HTTP_X_PJAX']) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
} else {
    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
}
?>
<?
global $APPLICATION;
CJSCore::Init('jquery');
$APPLICATION->AddHeadScript("/local/admin/assets/gadgets.js");
?>
<?
$arFilter = $APPLICATION->IncludeComponent(
    'pirogov:highload.filter',"",
    array(
        'HIBLOCK_ID' =>  '13',
        'CUSTOM_VIEW_SETTINGS' =>
        array(
            'UF_STATUS' => array(
                'TYPE' => 'select',
                'INPUT_CLASS' => 'custom_select',
                'ATTRIBUTES' => array(
                    'multiple' => 'multiple',
                ),
                'CSS_CLASS' => array(
                    'custom_gadget_select'
                )
            ),
        ),
        'FILTER_FIELDS' => array('UF_STATUS'),
        'AJAX_URL' => rel_path(__FILE__)
    )
);
?>
<?
if (isset($_REQUEST['page-key']) && intval($_REQUEST[$_REQUEST['page-key']])) {
    $page = intval($_REQUEST[$_REQUEST['page-key']]);
} else {
    $page = 1;
}
if (isset($_REQUEST['apply_filter'])) BXHelper::start_ajax_block();?>
<?
$APPLICATION->IncludeComponent("pirogov:highload.controller", "user_claims_list", Array(
        'NAMESPACE' => 'CUSTOM',
        'HL_ENTITY' => 'Y',
        'ENTITY' => 'RegisterClaimsTable',
        'MODULES' => array('iblock', 'highloadblock'),
        'LIST_FILTER' => $arFilter,
        'PAGE_NUMBER' => $page,
        'PAGE_LIMIT' => 15,
        'PAGER_TEMPLATE' => 'claims_list',
        'AJAX_URL' => rel_path(__FILE__)
    ),
    false
);
?>
<?if (isset($_REQUEST['apply_filter'])) BXHelper::end_ajax_block(false,100,'.table-container', true);?>