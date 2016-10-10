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
 */

use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Entity\OrderItemTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\PdfTable;
use Hogart\Lk\Helper\Template\Money;

$order = $arResult['order'];
?>

<div class="row full-height" data-stick-parent>
    <div class="col-sm-9">
        <div class="row spacer-20 order-line">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <h4>
                            <span class="title">
                                <?= OrderTable::showName($order) ?>
                            </span>
                            <sup><span class="label label-primary"><?= OrderTable::getTypeText($order['type']) ?></span></sup>
                            <sup><span class="label label-info"><?= OrderTable::getStateText($order['state']) ?></span></sup>
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
                    <div class="col-sm-4">
                        <div class="h5">Общая сумма: <abbr title="Общая сумма по заказу"><span class="money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= Money::show((float)$order['totals']['items']) ?></span></abbr></div>
                        <? if ($order['state'] == OrderTable::STATE_NORMAL): ?>
                            <? if ($order['totals']['release'] != 0): ?>
                                <div class="h5">Оплачено: <span class="money<?= ($order['c_currency_code'] == "RUB" ? "" : "-eur") ?>"><?= Money::show((float)$order['totals']['payments']) ?></span> (<?= (round($order['totals']['payments'] / $order['totals']['items'] * 100, 0)) ?>%)</div>
                                <div class="h5">К оплате: <span class="money<?= ($order['c_currency_code'] == "RUB" ? "" : "-eur") ?>"><?= Money::show((float)$order['totals']['release']) ?></span></div>
                            <? else : ?>
                                <div class="h5 color-green">Заказ оплачен полностью</div>
                            <? endif; ?>
                        <? endif; ?>
                    </div>
                    <div class="col-sm-2 text-right pull-right">
                        <? if ($order['state'] == OrderTable::STATE_NORMAL): ?>
                            <div class="h5"><span class="label label-default"><?= OrderTable::getStatusText($order['status']) ?></span></div>
                            <div class="h5"><?= OrderTable::getShipmentByFlag($order['shipment_flag']) ?></div>
                        <? endif; ?>
                    </div>
                </div>
                <div class="row spacer-20">
                    <div class="col-sm-6 comment">
                        <? if (!empty($order['note'])): ?>
                            <p class="h6">
                                <i class="fa fa-comment" aria-hidden="true"></i>
                                <i><?= $order['note'] ?></i>
                            </p>
                        <? endif; ?>
                    </div>
                    <div class="col-sm-3">
                        <? if ($order['guid_id'] && $order['state'] == OrderTable::STATE_NORMAL && $order['totals']['release']): ?>
                            <?= \Hogart\Lk\Helper\Template\Dialog::Button("order-payment", '<i class="fa fa-money" aria-hidden="true"></i> Оплатить', "btn btn-primary")?>
                        <? endif; ?>
                    </div>
                    <div class="col-sm-3 text-right pull-right">
                        <? if ($order['totals']['release'] <= 0 && $order['state'] == OrderTable::STATE_NORMAL && OrderTable::isProvideShipmentFlag($order['shipment_flag'], OrderItemTable::STATUS_IN_RESERVE)): ?>
                            <a href="/account/orders/shipment/<?= $order['s_XML_ID'] ?>/" class="btn btn-primary">Отгрузить</a>
                        <? endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <? foreach ($order['items'] as $item_group => $items): ?>
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
                                                <div class="header-info col-sm-8 pull-right">
                                                    <div class="pull-right text-right">Вес: <?= $order['totals']['weight'][$item_group] ?> кг.</div>
                                                    <div class="pull-right text-right">Объем: <?= $order['totals']['volume'][$item_group] ?> м<sup>3</sup></div>
                                                    <div class="pull-right text-right">Итого: <span class="money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= Money::show($order['totals']['group'][$item_group]) ?></span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <table cellspacing="0" width="100%" class="table table-hover table-bordered table-condensed" data-table>
                                            <thead>
                                            <tr>
                                                <th>№</th>
                                                <th class="reorder">Арт.</th>
                                                <th width="100%">Наименование</th>
                                                <th>Статус</th>
                                                <th class="text-center">Кол-во</th>
                                                <th>Ед.</th>
                                                <th>Цена</th>
                                                <th class="text-center">Цена&nbsp;с&nbsp;уч. скидки</th>
                                                <th>НДС <?= ($order['c_vat_include'] ? "включен" : "сверху") ?></th>
                                                <th>Сумма</th>
                                                <th>Вес</th>
                                                <th>Объем</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <? foreach ($items as $k => $item): ?>
                                                <tr id="<?= $item['id'] ?>" data-guid="<?= $item['id'] ?>">
                                                    <td><?= ($k + 1) ?></td>
                                                    <td class="text-nowrap"><?= $item['props']['sku']['VALUE'] ?></td>
                                                    <td><a target="_blank" href="<?= $item['url'] ?>"><?= $item['NAME'] ?></a></td>
                                                    <td><span class="label label-<?= OrderItemTable::getStatusColor($item['status']) ?>"><?= OrderItemTable::showStatusText($item['status']) ?></span></td>
                                                    <td class="text-nowrap"><?= $item['count'] ?></td>
                                                    <td><?= $order['measures'][$item['product']['MEASURE']] ?></td>
                                                    <td class="text-nowrap money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= $item['price'] ?></td>
                                                    <td class="text-nowrap money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= $item['discount_price'] ?></td>
                                                    <td class="text-nowrap money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= ($item['total_vat']) ?></td>
                                                    <td class="text-nowrap money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= ($item['total']) ?></td>
                                                    <td class="text-nowrap"><?= round($item['product']['WEIGHT'] * $item['count'], 2) ?> кг. </td>
                                                    <td class="text-nowrap"><?= (round($item['product']['WIDTH'] * $item['product']['LENGTH'] * $item['product']['HEIGHT'] / pow(1000, 3) * $item['count'], 2)) ?> м<sup>3</sup></sup></td>
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
            </div>

        </div>
    </div>
    <div class="col-sm-3">
        <div data-stick-block>
            <div class="menu">
                <ul class="fa-ul">
                    <li>
                        <a href="#">
                            <i class="fa fa-li fa-question fa-lg text-warning" aria-hidden="true"></i>
                            Задать вопрос менеджеру
                        </a>
                    </li>
                    <li>
                        <a href="<?= $APPLICATION->GetCurPage(false) . "?action=order-kp" ?>">
                            <i class="fa fa-li fa-print fa-lg text-warning" aria-hidden="true"></i>
                            <?= ($order['pdf'][PdfTable::TYPE_KP] ? "Печать" : "Запросить") ?> КП
                        </a>
                    </li>
                    <? if (!empty($order['pdf'][PdfTable::TYPE_BILL])) :?>
                    <li>
                        <a href="/account/orders/pdf/<?= $order['id'] ?>/<?= $order['pdf'][PdfTable::TYPE_BILL]['guid_id'] ?>">
                            <i class="fa fa-li fa-lg fa-print text-warning" aria-hidden="true"></i>
                            Печать счета на оплату
                        </a>
                    </li>
                    <? endif; ?>
                    <li>
                        <a data-confirmation="copy-to-cart" href="/account/orders/?copy_to_cart=<?= $order['id'] ?>&state=active">
                            <i class="fa fa-li fa-shopping-cart fa-lg text-primary" aria-hidden="true"></i>
                            Копировать в корзину
                        </a>
                    </li>

                    <? if ($order['state'] == OrderTable::STATE_DRAFT): ?>
                        <li>
                            <a data-confirmation="delete" href="/account/orders/?delete=<?= $order['id'] ?>&state=active">
                                <i class="fa fa-li fa-remove fa-lg text-danger" aria-hidden="true"></i>
                                Удалить черновик
                            </a>
                        </li>
                    <? else: ?>
                        <li>
                            <a href="/account/orders/active/index.php?copy_to_draft=<?= $order['id'] ?>">
                                <i class="fa fa-li fa-edit fa-lg text-primary" aria-hidden="true"></i>
                                Копировать в черновики
                            </a>
                        </li>
                    <? endif; ?>
                </ul>
            </div>
        </div>
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

<? \Hogart\Lk\Helper\Template\Dialog::Start('order-payment', [
    'dialog-options' => 'closeOnOutsideClick: false, closeOnEscape: false, closeOnConfirm: false',
    'title' => 'Подтверждение оплаты'
])?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="order-payment" method="post">
    <? include __DIR__ . "/forms/payment.php" ?>
    <input type="hidden" name="action" value="order-payment">
</form>
<?

$id = \Hogart\Lk\Helper\Template\Dialog::$id;
$handler =<<<JS
    (function() {
      var form = $('[data-remodal-id="$id"] form');
      var inst = $('[data-remodal-id="$id"]').remodal();
      form.validator();
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('opening', $handler);

\Hogart\Lk\Helper\Template\Dialog::End(['confirm' => false])
?>
