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
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Helper\Template\Ajax;
use Hogart\Lk\Helper\Template\Money;

$order = $arResult['order'];
?>
<script>var order_id = <?= $order['_id'] ?>; </script>
<div class="row full-height" data-stick-parent>
    <div class="col-sm-12">
        <div id="order-edit" class="row spacer-20 order-line">
            <? $order_edit_node = Ajax::Start($component) ?>
            <div class="col-sm-9">
                <div class="row spacer-40 order-line__header">
                    <div class="col-sm-6">
                        <h4>
                            <a href="/account/order/<?= $order['_id'] ?>">
                                <span class="title">
                                    <?= OrderTable::showName($order, '_') ?>
                                </span>
                            </a>
                        </h4>
                        <div><?= $order['co_name'] ?></div>
                        <div><?= ContractTable::showName($order, false, 'c_') ?></div>
                        <div>Отгрузка со склада: <u><?= $order['s_TITLE'] ?></u> </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="h5">Общая сумма: <abbr title="Общая сумма по заказу"><span class="money<?= ($order['c_currency_code'] == "RUB" ? "" : "-eur") ?>"><?= number_format((float)$order['totals']['items'], 2, '.', ' ') ?></span></abbr></div>
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
                                                <th></th>
                                                <th class="reorder">Арт.</th>
                                                <th width="100%">Наименование</th>
                                                <th class="text-center">Кол-во</th>
                                                <th class="measure">Ед.</th>
                                                <th class="t-money">Цена</th>
                                                <th class="text-center">Скидка %</th>
                                                <th class="t-money text-center">Цена&nbsp;с&nbsp;уч. скидки</th>
                                                <th class="t-money">НДС <?= ($order['c_vat_include'] ? "включен" : "сверху") ?></th>
                                                <th class="t-money">Сумма</th>
                                                <th>Вес</th>
                                                <th>Объем</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <? foreach ($items as $k => $item): ?>
                                                <tr class="<?= ($item['is_new'] ? 'new' : '') ?> <?= ($item['not_editable'] ? 'disabled' : '') ?>" id="<?= $item['guid_id'] ?>" data-guid="<?= $item['guid_id'] ?>">
                                                    <td><?= ($k + 1) ?></td>
                                                    <td></td>
                                                    <td class="text-nowrap"><?= $item['props']['sku']['VALUE'] ?></td>
                                                    <td>
                                                        <? if ($item['ACTIVE'] == 'N'): ?>
                                                            <?= $item['NAME'] ?>
                                                        <? else: ?>
                                                            <a target="_blank" href="<?= $item['url'] ?>"><?= $item['NAME'] ?></a>
                                                        <? endif; ?>
                                                    </td>
                                                    <td class="text-nowrap">
                                                        <? if (!$item['not_editable']): ?>
                                                        <input class="form-control input-sm"
                                                            id="quantity-<?= $item['guid_id'] ?>"
                                                            <?= Ajax::OnEvent(
                                                                'changeapply',
                                                                'order-edit',
                                                                $order_edit_node->getId(),
                                                                [
                                                                    'order_id' => $order['_id'],
                                                                    'item_id' => $item['guid_id'],
                                                                    'action' => 'change_quantity',
                                                                    'quantity' => 'javascript:function (element) { return $(element).val(); }',
                                                                ]
                                                            ) ?>
                                                            data-change-apply
                                                            data-change-discard="false"
                                                            type="number" min="1" max="9999" size="3" maxlength="3" value="<?= $item['count'] ?>">
                                                        <? else: ?>
                                                            <div class="text-center">
                                                                <?= $item['count'] ?>
                                                            </div>
                                                        <? endif; ?>
                                                    </td>
                                                    <td><?= $order['measures'][$item['product']['MEASURE']] ?></td>
                                                    <td class="text-nowrap money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= $item['price'] ?></td>
                                                    <td class="text-center">
                                                        <? if (!$item['not_editable']): ?>
                                                        <input tabindex="-1" class="form-control input-sm"
                                                           id="discount-<?= $item['guid_id'] ?>"
                                                            <?= Ajax::OnEvent(
                                                                'changeapply',
                                                                'order-edit',
                                                                $order_edit_node->getId(),
                                                                [
                                                                    'order_id' => $order['_id'],
                                                                    'item_id' => $item['guid_id'],
                                                                    'action' => 'change_discount',
                                                                    'discount' => 'javascript:function (element) { return $(element).val(); }',
                                                                ]
                                                            ) ?>
                                                           data-change-apply
                                                           data-change-discard="false"
                                                           type="number" min="0" max="<?= $item['max_discount'] ?>" size="2" maxlength="2" value="<?= $item['discount'] ?>">
                                                        <? else: ?>
                                                            <?= $item['discount']['discount'] ?>&nbsp;%
                                                        <? endif; ?>
                                                    </td>
                                                    <td class="text-nowrap money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= $item['discount_price'] ?></td>
                                                    <td class="text-nowrap money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= ($item['total_vat']) ?></td>
                                                    <td class="text-nowrap money-<?= strtolower($order['currency']['CURRENCY']) ?>"><?= ($item['total']) ?></td>
                                                    <td class="text-nowrap"><?= round($item['product']['WEIGHT'] * $item['count'], 2) ?> кг. </td>
                                                    <td class="text-nowrap"><?= (round($item['product']['WIDTH'] * $item['product']['LENGTH'] * $item['product']['HEIGHT'] / pow(1000, 3) * $item['count'], 2)) ?> м<sup>3</sup></sup></td>
                                                </tr>
                                            <? endforeach; ?>
                                            </tbody>
                                        </table>
                                        <div class="post-table-footer">
                                            <div class="row vertical-align">
                                                <div class="col-sm-8">
                                                    <div class="add-item-simple center-between">
                                                        <div class="add-item-label text-nowrap">Быстрое добавление</div>
                                                        <input required
                                                            <?= Ajax::OnEvent(
                                                                'typeaheadselect',
                                                                'order-edit',
                                                                $order_edit_node->getId(),
                                                                [
                                                                    'order_id' => $order['_id'],
                                                                    'item_group' => $item_group,
                                                                    'action' => 'add_item_simple',
                                                                    'sku' => 'javascript:function (element) { return $(element).data("sku"); }',
                                                                    'xml_id' => 'javascript:function (element) { return $(element).data("xml_id"); }',
                                                                ]
                                                            ) ?>
                                                               type="text" class="quick-order-add form-control" placeholder="Введите артикул/наименование">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <? /*Ajax::Link(
                                                        '<i class="fa fa-plus fa-lg text-primary" aria-hidden="true"></i> Добавить из файла',
                                                        'order-edit',
                                                        $order_edit_node->getId(),
                                                        [
                                                            'order_id' => $order['_id'],
                                                            'action' => 'add_items_file',
                                                            'item' => null,
                                                            'new_item_group' => null,
                                                            'copy' => null
                                                        ],
                                                        '',
                                                        Ajax::DIALOG_AJAX_LINK,
                                                        [
                                                            'title' => 'Добавление позиций из файла',
                                                            'template_file' => __DIR__ . "/forms/add-items.php",
                                                            'template_vars' => ['order' => $order, 'item_group' => $item_group],
                                                            'dialog_keys' => [$order['_id']],
                                                            'dialog_event_hogart.lk.openajaxlinkdialog' => 'openLinkAddItemsDialog',
                                                        ]
                                                    ) */ ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? endforeach; ?>
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
                </div>
            </div>
            <div class="col-sm-3">
                <div data-stick-block>
                    <div class="menu">
                        <ul class="fa-ul">
                            <? if ($order['is_changed']): ?>
                                <li data-save-edit>
                                    <?=
                                    \Hogart\Lk\Helper\Template\Dialog::Link("edit-dialog", "Сохранить/Отменить", "btn btn-primary");
                                    ?>
                                </li>
                            <? endif; ?>
                            <li data-set-max_discounts>
                                <i class="fa fa-li fa-percent fa-lg text-primary" aria-hidden="true"></i>
                                <?= Ajax::Link(
                                    'Установить max. скидки',
                                    'order-edit',
                                    $order_edit_node->getId(),
                                    [
                                        'order_id' => $order['_id'],
                                        'action' => 'set_max_discounts',
                                        'item' => null,
                                        'new_item_group' => null,
                                        'copy' => null
                                    ]
                                ) ?>
                            </li>
                            <li data-delete-button>
                                <i class="fa fa-li fa-trash fa-lg text-danger" aria-hidden="true"></i>
                                <?= Ajax::Link(
                                    'Удалить строки',
                                    'order-edit',
                                    $order_edit_node->getId(),
                                    [
                                        'order_id' => $order['_id'],
                                        'item' => 'javascript:function(element) { return getSelectedOrderRows(element); } ',
                                        'action' => 'delete_items',
                                        'new_item_group' => null,
                                        'quantity' => null,
                                        'item_id' => null,
                                        'new_order' => null
                                    ]
                                ) ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <? Ajax::End($order_edit_node->getId()) ?>
        </div>
    </div>

</div>

<!-- Диалог выхода со страницы -->
<? \Hogart\Lk\Helper\Template\Dialog::Start("edit-dialog", [
    'dialog-options' => 'hashTracking: false, closeOnOutsideClick: false, closeOnConfirm: false, closeOnCancel: false, closeOnEscape: false',
    'title' => 'Подтвердить редактирование?',
]) ?>
<form action="<?= $APPLICATION->GetCurPage(false) ?>" name="edit-dialog" method="post">
    <input type="hidden" name="action" value="">
</form>

<button data-remodal-action="stay" class="btn btn-warning remodal-stay">Продолжить редактирование</button>
<?
$id = \Hogart\Lk\Helper\Template\Dialog::$id;
$handler =<<<JS
    (function() {
      var inst = $('[data-remodal-id="$id"]').remodal();
      $('[data-remodal-action="stay"]', inst.\$modal)
        .off('click')
        .on('click', function () {
            inst.close();        
        });
      var action = $('[name="action"]', inst.\$modal);
      action.val(action.defaultValue);
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('opening', $handler);
$handler =<<<JS
    (function() {
      var inst = $('[data-remodal-id="$id"]').remodal();
      $('[name="action"]', inst.\$modal).val('apply_edit');
      $('form', inst.\$modal).submit();
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('confirmation', $handler);
$handler =<<<JS
    (function() {
      var inst = $('[data-remodal-id="$id"]').remodal();
      $('[name="action"]', inst.\$modal).val('cancel_edit');
      $('form', inst.\$modal).submit();
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('cancellation', $handler);
\Hogart\Lk\Helper\Template\Dialog::End([
    'end_breaks' => false,
    'confirm_text' => 'Сохранить изменения',
    'cancel_text' => 'Отменить изменения',
])
?>
<!-- Конец диалога выхода со страницы -->