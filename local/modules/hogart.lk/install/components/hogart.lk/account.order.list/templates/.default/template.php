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
use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\OrderItemTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Helper\Template\Ajax;
?>
<div class="row" data-loader-orders style="position: relative">
    <div data-loader-wrapper="[data-loader-orders]" id="orders-list" class="col-sm-9 order-list">
        <? $ordersNode = Ajax::Start($component); ?>
        <? foreach ($arResult['orders'] as $k => $order): ?>
        <div class="row spacer-20 order-line">
            <div class="col-sm-12">
                <? include dirname(__FILE__) . "/order-header.php" ?>
            </div>
        </div>
        <div class="row spacer-20">
            <div class="col-sm-12">
                <div class="delimiter color-green"></div>
            </div>
        </div>
        <? endforeach; ?>
        <div class="row text-center">
            <div class="col-sm-12">
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:main.pagenavigation",
                    "hogart.lk.bootstrap",
                    array(
                        "NAV_OBJECT" => $arParams['nav'],
                        "SEF_MODE" => "N",
                        "AJAX_NODE" => $ordersNode,
                        "AJAX_CONTAINER" => "orders-list"
                    ),
                    false
                );
                ?>
            </div>
        </div>
        <? Ajax::End($ordersNode->getId()); ?>
    </div>
    <div class="col-sm-3 order-filter aside">
        <? include __DIR__ . "/filter.php" ?>
    </div>
</div>

<? \Hogart\Lk\Helper\Template\Dialog::Start('copy-to-cart', [
    'dialog-options' => 'hashTracking: false, closeOnOutsideClick: false, closeOnEscape: false, closeOnConfirm: false',
    'title' => 'Подтверждение копирования'
])?>
    <p>
        Текущая корзина будет удалена!
    </p>
<?
\Hogart\Lk\Helper\Template\Dialog::End()
?>

<? \Hogart\Lk\Helper\Template\Dialog::Start('delete', [
    'dialog-options' => 'hashTracking: false, closeOnOutsideClick: false, closeOnEscape: false, closeOnConfirm: false',
    'title' => 'Подтверждение удаления'
])?>
<p>
    Заказ будет удален!
</p>
<?
\Hogart\Lk\Helper\Template\Dialog::End()
?>
