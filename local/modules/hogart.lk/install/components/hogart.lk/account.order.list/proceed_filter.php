<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 26/09/16
 * Time: 17:50
 *
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 * @ver array $companies
 */

use Hogart\Lk\Helper\Template\Ajax;

$arParams['filter'] = [];
$ajaxId = Ajax::GetAjaxId($this);

if (Ajax::isAjax($ajaxId) && $_REQUEST['action'] == 'filter') {
    if (isset($_REQUEST['status'])) {
        $arParams['filter']['=status'] = $_REQUEST['status'];
    }
    if (!empty($_REQUEST['company'])) {
        $arParams['filter']['=contract.company_id'] = $_REQUEST['company'];
    }
    if (!empty($_REQUEST['store'])) {
        $arParams['filter']['=store_guid'] = $_REQUEST['store'];
    }
    if (isset($_REQUEST['type'])) {
        $arParams['filter']['=type'] = $_REQUEST['type'];
    }
    if (!empty($_REQUEST['number'])) {
        $_REQUEST['number'] = htmlspecialchars($_REQUEST['number']);
        $arParams['filter']['=number'] = $_REQUEST['number'];
    }

    if (!empty($_REQUEST['date_from'])) {
        $_REQUEST['date_from'] = htmlspecialchars($_REQUEST['date_from']);
        $date = new \Bitrix\Main\Type\DateTime($_REQUEST['date_from'], HOGART_DATE_FORMAT);
        $_REQUEST['date_from'] = $date->format(HOGART_DATE_FORMAT);
        $arParams['filter']['>=order_date'] = $date;
    }
    if (!empty($_REQUEST['date_to'])) {
        $_REQUEST['date_to'] = htmlspecialchars($_REQUEST['date_to']);
        $date = new \Bitrix\Main\Type\DateTime($_REQUEST['date_to'], HOGART_DATE_FORMAT);
        $_REQUEST['date_to'] = $date->format(HOGART_DATE_FORMAT);
        $date->add("+1 day");
        $arParams['filter']['<order_date'] = $date;
    }
}
