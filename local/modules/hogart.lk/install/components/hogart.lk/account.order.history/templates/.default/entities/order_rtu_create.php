<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 03/10/2016
 * Time: 03:24
 *
 * @var array $order_rtu
 * @var array $items
 * @var array $measures
 */

use Hogart\Lk\Entity\OrderRTUTable;
use Hogart\Lk\Entity\AddressTable;

$_items = $items;
?>
<div class="row spacer"></div>
<div class="row">
    <div class="col-sm-12 h5">
        <b>Статус:</b>
        <?= OrderRTUTable::getDeliveryTypeText($order_rtu['delivery_type'])?>
    </div>
</div>
<? if ($order_rtu['status'] == OrderRTUTable::STATUS_CANCEL): ?>
    <div class="row">
        <div class="col-sm-12 h5">
            <b>Причина отклонения:</b>
            <?= $order_rtu['refuse_reason'] ?>
        </div>
    </div>
<? endif; ?>
<div class="row">
    <div class="col-sm-12 h5">
        <b>Тип доставки:</b>
        <?= OrderRTUTable::getDeliveryTypeText($order_rtu['delivery_type'])?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 h5">
        <b>Планируемая дата отгрузки:</b>
        <?= $order_rtu['plan_date']->format(HOGART_DATE_FORMAT) . " " . $order_rtu['plan_time'] ?>
    </div>
</div>

<? if ($order_rtu['delivery_type'] == OrderRTUTable::DELIVERY_OUR): ?>
<div class="row">
    <div class="col-sm-12 h5">
        <b>Адрес доставки:</b>
        <?= AddressTable::getValue($order_rtu, 'address_') ?>
    </div>
</div>
    <? if ($order_rtu['is_tk']): ?>
        <div class="row">
            <div class="col-sm-12 h5">
                <b>Транспортная компания:</b>
                <?= $order_rtu['tk_name'] ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 h5">
                <b>Адрес транспортной компании:</b>
                <?= AddressTable::getValue($order_rtu, 'tk_address_') ?>
            </div>
        </div>
    <? endif; ?>
<? endif; ?>

<div class="row">
    <div class="col-sm-12">
        <? foreach ($_items as $item_group => $items): ?>
            <div class="row" data-order-group-wrapper>
                <div class="col-sm-12 order-item-list-wrapper">
                    <div data-item-group="<?= $item_group ?>">
                        <div class="pre-table-header">
                            <div class="row vertical-align">
                                <div class="col-sm-4">
                                    <div class="category-name">
                                        <span><?= $item_group ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table cellspacing="0" width="100%" class="table table-hover table-bordered table-condensed" data-table>
                            <thead>
                            <tr>
                                <th>№</th>
                                <th class="reorder">Арт.</th>
                                <th width="100%">Наименование</th>
                                <th class="text-center text-nowrap">Кол-во</th>
                                <th>Ед.</th>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach ($items as $k => $item): ?>
                                <tr id="<?= $item['id'] ?>" data-guid="<?= $item['id'] ?>">
                                    <td><?= ($k + 1) ?></td>
                                    <td class="text-nowrap"><?= $item['props']['sku']['VALUE'] ?></td>
                                    <td><a target="_blank" href="<?= $item['url'] ?>"><?= $item['NAME'] ?></a></td>
                                    <td class="text-nowrap"><?= $item['count'] ?></td>
                                    <td><?= $measures[$item['product']['MEASURE']] ?></td>
                                </tr>
                            <? endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>
