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
                <div class="row">
                    <div class="col-sm-6">
                        <h4>
                            <a href="/account/order/<?= $order['id'] ?>">
                                <span class="title">
                                    <?= OrderTable::showName($order) ?>
                                </span>
                            </a>
                            <sup><span class="label label-primary"><?= OrderTable::getTypeText($order['type']) ?></span></sup>
                            <? if ($order['history'] > 0 && in_array($order['state'], [OrderTable::STATE_ARCHIVE, OrderTable::STATE_NORMAL])): ?>
                            <sup>
                                <a href="/account/order/<?= $order['id'] ?>/history/">
                                    <span class="label label-warning">
                                        <i class="fa fa-history"></i>
                                    </span>
                                </a>
                            </sup>
                            <? endif; ?>
                        </h4>
                        <div><?= $order['co_name'] ?></div>
                        <div><?= ContractTable::showName($order, false, 'c_') ?></div>
                        <div>Отгрузка со склада: <u><?= $order['s_TITLE'] ?></u> </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="h5">Общая сумма: <abbr title="Общая сумма по заказу"><span class="money<?= ($order['c_currency_code'] == "RUB" ? "" : "-eur") ?>"><?= number_format((float)$order['totals']['items'], 2, '.', ' ') ?></span></abbr></div>
                        <? if ($order['state'] == OrderTable::STATE_NORMAL): ?>
                            <? if ($order['totals']['release'] != 0): ?>
                                <div class="h5">Оплачено: <span class="money<?= ($order['c_currency_code'] == "RUB" ? "" : "-eur") ?>"><?= number_format((float)$order['totals']['payments'], 2, '.', ' ') ?></span> (<?= (round($order['totals']['payments'] / $order['totals']['items'] * 100, 0)) ?>%)</div>
                                <div class="h5">К оплате: <span class="money<?= ($order['c_currency_code'] == "RUB" ? "" : "-eur") ?>"><?= number_format((float)$order['totals']['release'], 2, '.', ' ') ?></span></div>
                            <? else : ?>
                                <div class="h5 color-green">Заказ оплачен полностью</div>
                            <? endif; ?>
                        <? endif; ?>
                    </div>
                    <div class="col-sm-3 text-right pull-right">
                        <? if ($order['state'] == OrderTable::STATE_NORMAL): ?>
                            <div class="h5">
                                <span class="label label-default"><?= OrderTable::getStatusText($order['status']) ?></span>
                            </div>
                            <div class="h5"><?= OrderTable::getShipmentByFlag($order['shipment_flag']) ?></div>
                        <? endif; ?>
                        <? if ($order['state'] == OrderTable::STATE_DRAFT): ?>
                            <div class="row spacer">
                                <div class="col-sm-12">
                                    <a data-confirmation="copy-to-cart" href="<?= $APPLICATION->GetCurPage() ?>?copy_to_cart=<?= $order['id'] ?>" class="btn btn-primary">Копировать в корзину</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a data-confirmation="delete" href="<?= $APPLICATION->GetCurPage() ?>?delete=<?= $order['id'] ?>" class="btn btn-danger">Удалить черновик</a>
                                </div>
                            </div>
                        <? endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 comment">
                        <? if (!empty($order['note'])): ?>
                        <p class="h6">
                            <i class="fa fa-comment" aria-hidden="true"></i>
                            <i><?= $order['note'] ?></i>
                        </p>
                        <? endif; ?>
                    </div>
                    <div class="col-sm-3">
                        <? if ($order['state'] == OrderTable::STATE_NORMAL && $order['totals']['release']): ?>
                        <button class="btn btn-primary"><i class="fa fa-money" aria-hidden="true"></i> Оплатить</button>
                        <? endif; ?>
                    </div>
                    <div class="col-sm-3 text-right pull-right">
                        <? if ($order['state'] == OrderTable::STATE_NORMAL && OrderTable::isProvideShipmentFlag($order['shipment_flag'], OrderItemTable::STATUS_IN_RESERVE)): ?>
                        <a href="/account/orders/shipment/<?= $order['s_XML_ID'] ?>/" class="btn btn-primary">Отгрузить</a>
                        <? endif; ?>
                    </div>
                </div>
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
