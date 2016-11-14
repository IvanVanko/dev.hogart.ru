<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 13/09/16
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

use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Helper\Template\Ajax;
use Hogart\Lk\Helper\Template\ViewNode;
use Hogart\Lk\Helper\Template\Money;
use Bitrix\Main\EventManager;

?>

<? $this->SetViewTarget('empty_cart') ?>
<? if ($arParams['isEmpty']): ?>
    <div class="row">
        <div class="col-sm-12 full-height empty-cart color-green">
            <div class="h2">
                    <span class="fa-stack fa-lg">
                      <i class="fa fa-square fa-stack-2x"></i>
                      <i class="fa fa-shopping-cart fa-inverse fa-stack-1x"></i>
                    </span>
                Корзина пуста
            </div>
            <br>
            <a href="/account/orders/" role="button" class="btn btn-primary btn-lg">Перейти в заказы</a>
        </div>
    </div>
<? endif; ?>
<? $this->EndViewTarget() ?>

<?
EventManager::getInstance()->addEventHandler("hogart.lk", ViewNode::EVENT_ON_AJAX_VIEW_NODE, function () {
    global $APPLICATION;
    if ((!defined('ADMIN_SECTION') || !ADMIN_SECTION) && !preg_match('%application/json%', $_SERVER['HTTP_ACCEPT'])) {
        $APPLICATION->ShowViewContent('empty_cart');
    }
});
?>

<div class="cart-wrapper row">
    <div id="carts" class="<?= (!$arParams['isEmpty'] ? "full-height" : "") ?> col-sm-12">
        <? $carts_node = Ajax::Start($component, ['step1']) ?>
        <div class="row spacer">
            <div class="col-sm-9">
                <? include __DIR__ . "/header.php"; ?>
            </div>
        </div>

        <? foreach ($arResult['carts'] as &$cart): ?>
            <div id="<?= $cart['guid_id'] ?>" data-cart="<?= $cart['guid_id'] ?>" class="row spacer-all-20 <?= (empty($cart['items']) ? "hidden" : "") ?>">
                <?
                $cart['ajax_node'] = Ajax::Start($component, ['cart_id' => $cart['guid_id']], $carts_node);
                ?>
                <? if (!empty($cart['items'])): ?>
                    <div class="col-sm-12">
                        <!-- разделитель -->
                        <div class="row spacer-20">
                            <div class="col-sm-9">
                                <div class="delimiter color-green"></div>
                            </div>
                        </div>
                        <!-- шапка каждой корзины -->
                        <div class="row spacer-20">
                            <div class="col-sm-9">
                                <div class="row">
                                    <!-- Выбор договора и склада -->
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select
                                                    <?= Ajax::OnEvent(
                                                        'change',
                                                        'carts',
                                                        $carts_node->getId(),
                                                        [
                                                            'cart_id' => $cart['guid_id'],
                                                            'contract_id' => 'javascript:function (element) { return $(element).val(); }',
                                                            'action' => 'change_contract'
                                                        ]
                                                    ) ?>
                                                    title="Выберете договор" name="contract" class="form-control selectpicker">
                                                    <? foreach ($arResult['contracts'] as $contract): ?>
                                                        <option <?= ($cart['contract_id'] == $contract['id'] ? "selected" : "") ?> data-content="<?= htmlspecialchars(ContractTable::showName($contract, true)) ?>" value="<?= $contract['id'] ?>"><?= htmlspecialchars(ContractTable::showName($contract, true)) ?></option>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <select
                                                    <?= Ajax::OnEvent(
                                                        'change',
                                                        'carts',
                                                        $carts_node->getId(),
                                                        [
                                                            'cart_id' => $cart['guid_id'],
                                                            'store_id' => 'javascript:function (element) { return $(element).val(); }',
                                                            'action' => 'change_store'
                                                        ]
                                                    ) ?>
                                                    title="Выберете склад" name="store" class="form-control selectpicker">
                                                    <? foreach ($arResult['stores'] as $store): ?>
                                                        <?
                                                        $store_name = $store['TITLE'];
                                                        if (!empty(trim($store['ADDRESS']))) {
                                                            $store_name .= '<span class="footer-text">' . $store['ADDRESS'] . '</span>';
                                                        }
                                                        $store_name = htmlspecialchars($store_name);
                                                        ?>
                                                        <option <?= ($cart['store_guid'] == $store['XML_ID'] ? "selected" : "") ?> data-content="<?= $store_name ?>" value="<?= $store['XML_ID'] ?>"><?= $store_name ?></option>
                                                        <? if ($cart['store_guid'] == $store['XML_ID']) $selected_store = $store; ?>
                                                    <? endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Цена по корзине -->
                                    <div class="total col-sm-3 text-right">
                                        <div class="h5">Итоговая сумма заказа</div>
                                        <span class="h4 money-<?= strtolower($cart['currency']['CURRENCY']) ?>">
                                            <?= Money::show($cart['total']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row spacer-40">
                            <div class="col-sm-9">
                                <? if ($cart['contract_id']): ?>
                                    <!-- Компания (если выбран договор) -->
                                    <span class="h4 company_name">
                                        <?= $cart['company_name'] ?>
                                    </span>
                                <? endif; ?>
                                <span class="h4 cart-type-badge">
                                    <sup><span class="label label-primary">Товары</span></sup>
                                </span>
                            </div>
                        </div>
                        <!-- основная зона корзины-->

                        <div class="row" data-stick-parent>
                            <div class="col-sm-10">
                                <? foreach ($cart['items'] as $item_group => $items): ?>
                                    <div class="row" data-cart-group-wrapper>
                                        <div class="col-sm-12 cart-item-list-wrapper">
                                            <div data-item-group="<?= $item_group ?>">
                                                <div class="pre-table-header">
                                                    <div class="row vertical-align">
                                                        <div class="col-sm-4">
                                                            <div class="category-name">
                                                                <input
                                                                    <?= Ajax::OnEvent(
                                                                        'changeapply',
                                                                        $cart['guid_id'],
                                                                        $cart['ajax_node']->getId(),
                                                                        [
                                                                            'cart_id' => $cart['guid_id'],
                                                                            'item_group' => $item_group,
                                                                            'action' => 'change_category',
                                                                            'new_item_group' => 'javascript:function (element) { return $(element).val(); }',
                                                                        ]
                                                                    ) ?>
                                                                    data-change-apply
                                                                    class="form-control" type="text" name="item_group" placeholder="<?= (!$item_group ? "Без группы" : "")?>" value="<?= $item_group ?>">
                                                            </div>
                                                        </div>
                                                        <div class="header-info col-sm-8 pull-right">
                                                            <div class="text-right">Итого: <span class="money-<?= strtolower($cart['currency']['CURRENCY']) ?>"><?= Money::show($cart['item_group_totals']['money'][$item_group]) ?></span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table
                                                    <?= Ajax::OnEvent(
                                                        'rowreordered',
                                                        $cart['guid_id'],
                                                        $cart['ajax_node']->getId(),
                                                        [
                                                            'cart_id' => $cart['guid_id'],
                                                            'action' => 'change_cart_order',
                                                            'new_order' => 'javascript:changeTableOrder'
                                                        ]
                                                    ) ?>
                                                    cellspacing="0" width="100%" class="table table-hover table-bordered table-condensed" id="cart-table-<?= $cart['guid_id'] ?>" data-table>
                                                    <thead>
                                                    <tr>
                                                        <th class="t-number">№</th>
                                                        <th></th>
                                                        <th></th>
                                                        <th class="reorder">Арт.</th>
                                                        <th class="t-name" width="100%">Наименование</th>
                                                        <th class="t-count text-center">Кол-во</th>
                                                        <th >Ед.</th>
                                                        <th class="t-money">Цена</th>
                                                        <? if (!empty($cart['contract_id'])) :?>
                                                        <th class="t-percent">Скидка %</th>
                                                        <th class="t-money text-center">Цена&nbsp;с&nbsp;уч. скидки</th>
                                                        <? endif; ?>
                                                        <th class="t-money">Сумма</th>

                                                        <? if (!empty($cart['store_guid'])) :?>
                                                            <th>Наличие</th>
                                                        <? endif; ?>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <? foreach ($items as $ii => $item): ?>
                                                        <tr id="<?= $item['guid_id'] ?>" data-guid="<?= $item['guid_id'] ?>">
                                                            <td><?= $item['item_group_position']?></td>
                                                            <td><span class="glyphicon glyphicon-move" aria-hidden="true"></span></td>
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
                                                                <input tabindex="<?= $ii + 1 ?>" class="form-control input-sm"
                                                                       id="quantity-<?= $item['guid_id'] ?>"
                                                                    <?= Ajax::OnEvent(
                                                                        'changeapply',
                                                                        $cart['guid_id'],
                                                                        $cart['ajax_node']->getId(),
                                                                        [
                                                                            'cart_id' => $cart['guid_id'],
                                                                            'item_id' => $item['guid_id'],
                                                                            'action' => 'change_quantity',
                                                                            'quantity' => 'javascript:function (element) { return $(element).val(); }',
                                                                        ]
                                                                    ) ?>
                                                                       data-change-apply
                                                                       data-change-discard="false"
                                                                       type="number" min="1" max="9999" size="3" maxlength="3" value="<?= $item['count'] ?>">
                                                            </td>
                                                            <td><?= $arResult['measures'][$item['product']['MEASURE']] ?></td>
                                                            <td class="text-nowrap money-<?= strtolower($cart['currency']['CURRENCY']) ?>"><?= $item['price'] ?></td>
                                                            <? if (!empty($cart['contract_id'])) :?>
                                                            <td>
                                                                <input tabindex="-1" class="form-control input-sm"
                                                                       id="discount-<?= $item['guid_id'] ?>"
                                                                    <?= Ajax::OnEvent(
                                                                        'changeapply',
                                                                        $cart['guid_id'],
                                                                        $cart['ajax_node']->getId(),
                                                                        [
                                                                            'cart_id' => $cart['guid_id'],
                                                                            'item_id' => $item['guid_id'],
                                                                            'action' => 'change_discount',
                                                                            'discount' => 'javascript:function (element) { return $(element).val(); }',
                                                                        ]
                                                                    ) ?>
                                                                       data-change-apply
                                                                       data-change-discard="false"
                                                                       type="number" min="0" max="<?= $item['discount']['max_discount'] ?>" size="3" maxlength="3" value="<?= $item['discount']['discount'] ?>">
                                                            </td>
                                                            <td class="text-nowrap money-<?= strtolower($cart['currency']['CURRENCY']) ?>"><?= $item['discount']['price'] ?></td>
                                                            <? endif; ?>
                                                            <td class="text-nowrap money-<?= strtolower($cart['currency']['CURRENCY']) ?>"><?= ($item['discount']['price'] * $item['count']) ?></td>
                                                            <? if (!empty($selected_store)) :?>
                                                                <td class="text-center">
                                                                    <div class="quantity-wrapper center-between">
                                                                        <div>
                                                                            <?
                                                                            $class = "color-primary";
                                                                            if ($item['NEGATIVE_AMOUNT'] > 0) {
                                                                                $class = "color-warning";
                                                                            }
                                                                            if ($item['STORE_AMOUNT'] <= 0) {
                                                                                $class = "color-danger";
                                                                            }
                                                                            ?>
                                                                            <i class="fa fa-circle <?= $class ?>"></i>
                                                                        </div>
                                                                        <div class="h6 text-nowrap text-right" style="margin-left: 5px">
                                                                            <div>
                                                                                <b><?= $selected_store["TITLE"] ?></b>
                                                                                <?= (int)$item['STORE_AMOUNT'] ?>
                                                                                <?= $arResult['measures'][$item['product']['MEASURE']] ?>
                                                                            </div>
                                                                            <? if ($item['STORE_ALL_AMOUNT'] - $item['STORE_AMOUNT'] > 0): ?>
                                                                            <div>
                                                                                <b>На других</b>
                                                                                <?= ($item['STORE_ALL_AMOUNT'] - $item['STORE_AMOUNT'])?>
                                                                                <?= $arResult['measures'][$item['product']['MEASURE']] ?>
                                                                            </div>
                                                                            <? endif; ?>
                                                                            <? if (!empty($item['STORE_TRANSIT']) && $item['NEGATIVE_AMOUNT'] > 0): ?>
                                                                            <div>
                                                                                <b>В пути</b>
                                                                                <?= (int)$item['STORE_TRANSIT'] ?>
                                                                                <?= $arResult['measures'][$item['product']['MEASURE']] ?>
                                                                            </div>
                                                                            <? endif; ?>
                                                                            <? if (!empty($item['STORE_RESERVE']) && $item['NEGATIVE_AMOUNT'] > 0): ?>
                                                                            <div>
                                                                                <b>Резерв</b>
                                                                                <?= (int)$item['STORE_RESERVE'] ?>
                                                                                <?= $arResult['measures'][$item['product']['MEASURE']] ?>
                                                                            </div>
                                                                            <? endif; ?>
                                                                            <? if (!empty($item['props']['days_till_receive']['VALUE'])): ?>
                                                                                <abbr title="Макс. срок поставки (в днях)">
                                                                                    <i class="glyphicon glyphicon-time"></i> <?= $item['props']['days_till_receive']['VALUE'] ?> дн.
                                                                                </abbr>
                                                                            <? endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            <? endif; ?>
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
                                                                        $cart['guid_id'],
                                                                        $cart['ajax_node']->getId(),
                                                                        [
                                                                            'cart_id' => $cart['guid_id'],
                                                                            'item_group' => $item_group,
                                                                            'action' => 'add_item_simple',
                                                                            'sku' => 'javascript:function (element) { return $(element).data("sku"); }',
                                                                        ]
                                                                    ) ?>
                                                                       type="text" class="quick-cart-add form-control" placeholder="Введите артикул/наименование">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <?= Ajax::Link(
                                                                '<i class="fa fa-plus fa-lg text-primary" aria-hidden="true"></i> Добавить из файла',
                                                                'carts',
                                                                $carts_node->getId(),
                                                                [
                                                                    'cart_id' => $cart['guid_id'],
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
                                                                    'template_vars' => ['cart' => $cart, 'item_group' => $item_group],
                                                                    'dialog_keys' => [$cart['guid_id']],
                                                                    'dialog_event_hogart.lk.openajaxlinkdialog' => 'openLinkAddItemsDialog',
                                                                ]
                                                            ) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <? endforeach; ?>
                            </div>
                            <div class="col-sm-2">
                                <div class="cart-group-menu" data-stick-block>
                                    <div class="menu">
                                        <ul class="fa-ul">

                                            <? $create_order_button = Ajax::Link(
                                                'Создать заказ',
                                                'carts',
                                                $carts_node->getId(),
                                                [
                                                    'cart_id' => $cart['guid_id'],
                                                    'action' => 'create_order',
                                                    'item' => null,
                                                    'new_item_group' => null,
                                                    'copy' => null
                                                ],
                                                'class="btn btn-primary"',
                                                Ajax::DIALOG_AJAX_LINK,
                                                [
                                                    'title' => 'Создание заказа',
                                                    'template_file' => __DIR__ . "/forms/add-order.php",
                                                    'template_vars' => ['cart' => $cart],
                                                    'dialog_event_hogart.lk.openajaxlinkdialog' => 'openLinkAddOrderDialog',
                                                ]
                                            ) ?>

                                            <? if (!empty($cart['contract_id']) && !empty($cart['store_guid'])): ?>
                                            <li data-create-order-button>
                                                <?= $create_order_button ?>
                                            </li>
                                            <? endif; ?>
                                            <? if (!empty($cart['store_guid'])): ?>
                                                <li data-store-check>
                                                    <i class="fa fa-li fa-remove fa-lg text-danger" aria-hidden="true"></i>
                                                    <?= Ajax::Link(
                                                        'Удалить отсутствующие на складе',
                                                        $cart['guid_id'],
                                                        $cart['ajax_node']->getId(),
                                                        [
                                                            'cart_id' => $cart['guid_id'],
                                                            'action' => 'delete_nostock_items',
                                                            'new_item_group' => null,
                                                            'quantity' => null,
                                                            'item_id' => null,
                                                            'new_order' => null
                                                        ]
                                                    ) ?>
                                                </li>
                                            <? endif; ?>
                                            <li data-reload>
                                                <i class="fa fa-li fa-refresh fa-lg text-primary" aria-hidden="true"></i>
                                                <?= Ajax::Link(
                                                    'Контроль наличия',
                                                    'carts',
                                                    $carts_node->getId(),
                                                    [
                                                        'cart_id' => $cart['guid_id'],
                                                        'action' => 'reload',
                                                        'item' => null,
                                                        'new_item_group' => null,
                                                        'copy' => null
                                                    ]
                                                ) ?>
                                            </li>
                                            <li data-copy-move-button>
                                                <i class="fa fa-li fa-copy fa-lg text-primary" aria-hidden="true"></i>
                                                <?= Ajax::Link(
                                                    'Группировать товары',
                                                    'carts',
                                                    $carts_node->getId(),
                                                    [
                                                        'cart_id' => $cart['guid_id'],
                                                        'action' => 'edit_items_group',
                                                        'item' => 'javascript:function(element) { return getSelectedCartRows(element); } ',
                                                        'new_item_group' => null,
                                                        'copy' => null
                                                    ],
                                                    '',
                                                    Ajax::DIALOG_AJAX_LINK,
                                                    [
                                                        'title' => 'Перенос в группу',
                                                        'template_file' => __DIR__ . "/forms/edit-item-group.php",
                                                        'template_vars' => ['cart' => $cart],
                                                        'dialog_keys' => [$cart['guid_id']],
                                                        'dialog_event_hogart.lk.openajaxlinkdialog' => 'openLinkEditItemGroupDialog',
                                                    ]
                                                ) ?>
                                            </li>
                                            <li data-delete-button>
                                                <i class="fa fa-li fa-trash fa-lg text-danger" aria-hidden="true"></i>
                                                <?= Ajax::Link(
                                                    'Удалить строки',
                                                    $cart['guid_id'],
                                                    $cart['ajax_node']->getId(),
                                                    [
                                                        'cart_id' => $cart['guid_id'],
                                                        'item' => 'javascript:function(element) { return getSelectedCartRows(element); } ',
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
                        </div>
                    </div>
                <? endif; ?>
                <? Ajax::End($cart['ajax_node']->getId()) ?>
            </div>
        <? endforeach; ?>

        <? Ajax::End($carts_node->getId()) ?>
    </div>
</div>
<? $APPLICATION->ShowViewContent('empty_cart'); ?>