<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global $APPLICATION
 *
 * @var array $order
 */

use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\OrderItemTable;
use Hogart\Lk\Helper\Template\Money;

?>
<div class="row spacer-20 order-line__header">
    <div class="col-sm-6">
        <h4>
            <a href="/account/order/<?= $order['id'] ?>">
                <span class="title">
                    <?= OrderTable::showName($order) ?>
                </span>
            </a>
            <sup class="hidden-xs">
                <? if ($order['state'] == OrderTable::STATE_NORMAL): ?>
                    <span class="label label-default label-xs"><?= OrderTable::getStatusText($order['status']) ?></span>
                <? endif; ?>
                <span class="label label-primary label-xs"><?= OrderTable::getTypeText($order['type']) ?></span>
            </sup>

            <? if (!$order['is_actual']): ?>
                <sup class="hidden-xs">
                    <span class="label label-danger label-xs">синхронизация</span>
                </sup>
            <? endif; ?>

            <? if ($order['history'] > 0 && in_array($order['state'], [OrderTable::STATE_ARCHIVE, OrderTable::STATE_NORMAL])): ?>
                <sup>
                    <span class="hidden-xs" style="padding-left: 5px;"><a href="/account/order/<?= $order['id'] ?>/history/" class="btn btn-warning btn-xs"><i class="fa fa-history"></i> История</a></span>
                </sup>
            <? endif; ?>

            <div class="visible-xs small" style="margin-top: 10px;">
                <? if ($order['state'] == OrderTable::STATE_NORMAL): ?>
                    <span class="label label-default label-xs"><?= OrderTable::getStatusText($order['status']) ?></span>
                <? endif; ?>
                <span class="label label-primary label-xs"><?= OrderTable::getTypeText($order['type']) ?></span>
                <? if (!$order['is_actual']): ?>
                    <span class="label label-danger label-xs">синхронизация</span>
                <? endif; ?>
                <? if ($order['history'] > 0 && in_array($order['state'], [OrderTable::STATE_ARCHIVE, OrderTable::STATE_NORMAL])): ?>
                    <span style="padding-left: 5px;"><a href="/account/order/<?= $order['id'] ?>/history/" class="btn btn-warning btn-xs" style="margin-bottom: 0"><i class="fa fa-history"></i></a></span>
                <? endif; ?>
            </div>


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
    <div class="col-sm-2 shipment-info">
        <? if ($order['state'] == OrderTable::STATE_NORMAL): ?>
            <div class="h5"><?= OrderTable::getShipmentByFlag($order['shipment_flag']) ?></div>
        <? endif; ?>
    </div>
    <div class="col-sm-1 text-right pull-right">
        <? if ($order['state'] == OrderTable::STATE_NORMAL): ?>
            <div class="hidden-xs h5">
                <span class="label label-default"><?= OrderTable::getStatusText($order['status']) ?></span>
            </div>
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
        <span class="hidden-xs label label-primary"><?= OrderTable::getTypeText($order['type']) ?></span>
    </div>
</div>
<div class="row spacer-20">
    <div class="col-sm-6">
        <?= $APPLICATION->GetViewContent('shipment-view-part') ?>
        <? if ($order['c_is_credit']): ?>
            <div style="font-weight: bold">Остаток кредит-лимита по договору: <span class="money<?= ($order['c_currency_code'] == "RUB" ? "" : "-eur") ?>"><?= Money::show($order['c_sale_max_money']) ?></span></div>
        <? endif; ?>
    </div>
    <div class="col-sm-3">
        <? if ($order['guid_id'] && $order['state'] == OrderTable::STATE_NORMAL && $order['totals']['release']): ?>
            <a href="<?= ($isListOfOrders ? "/account/order/{$order['id']}/#order-payment" : "javascript:void(0)") ?>"
               <?= ($isListOfOrders ? '' : 'data-remodal-target="order-payment"') ?>
               class="btn btn-primary"><i class="fa fa-money" aria-hidden="true"></i> Оплатить
            </a>
        <? elseif($order['totals']['release']): ?>
            <div class="h5 color-danger">
                Не выполнено условие по оплате
            </div>
        <? endif; ?>
    </div>
    <div class="col-sm-2">
        <? if ($order['is_actual'] && $order['sale_granted'] && ((!$order['c_is_credit'] && $order['sale_max_money'] > 0) || ($order['c_is_credit'] && OrderTable::isMaxMoneyValid($order))) && $order['state'] == OrderTable::STATE_NORMAL && OrderTable::isProvideShipmentFlag($order['shipment_flag'], OrderItemTable::STATUS_IN_RESERVE)): ?>
            <a href="/account/orders/shipment/<?= $order['s_XML_ID'] ?>/" class="btn btn-primary">Отгрузить</a>
        <? elseif (!empty($order['block_reason'])): ?>
            <div style="font-weight: bold" class="text-danger">
                <?= $order['block_reason'] ?>
            </div>
        <? endif; ?>
    </div>
</div>