<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/10/2016
 * Time: 16:52
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

use Hogart\Lk\Helper\Template\Dialog;
use Hogart\Lk\Helper\Template\Money;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\OrderTable;

//ini_set("xdebug.var_display_max_depth", -1);
//var_dump($arResult['orders']);

\Hogart\Lk\Helper\Template\Suggestions::init();

?>
<div class="row">
    <div class="col-sm-12">
        <? foreach ($arResult['orders'] as $store => $orders): ?>
            <div class="row spacer-20" data-store="<?= $store ?>" data-stick-parent>
                <div class="col-sm-9">
                    <div class="row spacer">
                        <div class="col-sm-12">
                            <h4>Выберите позиции для отгрузки со склада
                                <abbr title="<?= $arResult['stores'][$store]['ADDRESS'] ?>"><?= $arResult['stores'][$store]['TITLE'] ?></abbr>
                            </h4>
                            <div class="h5">
                                <?
                                $account_limit = floatval(\Hogart\Lk\Helper\Template\Account::getAccount()['sale_max_money']);
                                ?>
                                Кредитный лимит по аккаунту
                                <span data-account-credit-limit="<?= $account_limit ?>"
                                      class="money-rub"
                                >
                                    <?= Money::show($account_limit) ?>
                                </span>
                                (<span data-account-sale-selected class="money-rub">
                                    0
                                </span>)
                            </div>
                        </div>
                    </div>
                    <div class="row spacer">
                        <div class="col-sm-12">
                            <? foreach ($orders as $order): ?>
                                <div class="row spacer-20 order-line"
                                     data-order="<?= $order['id'] ?>"
                                     data-credit="<?= $order['c_is_credit'] ?>"
                                     data-company="<?= $order['co_id'] ?>"
                                     data-contract-id="<?= $order['c_id'] ?>"
                                >
                                    <div class="col-sm-12">
                                        <script>
                                            addresses = $.extend(addresses, <?= \CUtil::PhpToJSObject($order['addresses']) ?> || {});
                                        </script>
                                        <div class="row spacer-40 vertical-align">
                                            <div class="col-sm-8">
                                                <div>
                                                    <div class="checkbox-title">
                                                        <div class="checkbox checkbox-primary">
                                                            <input type="checkbox">
                                                            <label></label>
                                                        </div>
                                                        <h4>
                                                            <?= OrderTable::showName($order) ?>
                                                            <sup><span class="label label-primary"><?= OrderTable::getTypeText($order['type']) ?></span></sup>
                                                        </h4>
                                                    </div>
                                                    <div><?= $order['co_name'] ?></div>
                                                    <div><?= ContractTable::showName($order, false, 'c_') ?></div>
                                                    <? if (!empty($order['c_is_credit'])) :?>
                                                    <div class="h5">
                                                        Кредитный лимит по договору
                                                        <span data-contract-credit-limit="<?= floatval($order['c_sale_max_money']) ?>"
                                                              class="money-<?= strtolower($order['c_currency_code']) ?>"
                                                        >
                                                            <?= Money::show($order['c_sale_max_money']) ?>
                                                        </span>
                                                        (<span data-contract-sale-selected data-order="<?= $order['id'] ?>" class="money-<?= strtolower($order['currency']['CURRENCY']) ?>">
                                                            0
                                                        </span>)
                                                    </div>
                                                    <? endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 pull-right text-right">
                                                <? if ($order['sale_granted']): ?>
                                                    <div class="h5">
                                                        <div>
                                                            Доступна отгрузка на сумму
                                                            <span data-sale-max="<?= $order['sale_max_money'] ?>" data-order="<?= $order['id'] ?>" class="money-<?= strtolower($order['currency']['CURRENCY']) ?>">
                                                                <?= Money::show($order['sale_max_money']) ?>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            Выбрано на сумму:
                                                            <span data-sale-selected data-order="<?= $order['id'] ?>" class="money-<?= strtolower($order['currency']['CURRENCY']) ?>">
                                                                0
                                                            </span>
                                                        </div>
                                                    </div>
                                                <? endif; ?>
                                            </div>
                                        </div>
                                        <div class="row items">
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
                                                                        <th>Ед.</th>
                                                                        <th>Цена</th>
                                                                        <th class="text-center">Цена&nbsp;с&nbsp;уч. скидки</th>
                                                                        <th>НДС</th>
                                                                        <th>Сумма</th>
                                                                        <th>Вес</th>
                                                                        <th>Объем</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <? foreach ($items as $k => $item): ?>
                                                                        <tr class="" id="<?= $item['id'] ?>" data-default-count="<?= max(1, (int)$item['props']['default_count']['VALUE']) ?>" data-company="<?= $order['co_id'] ?>" data-order="<?= $order['id'] ?>">
                                                                            <td><?= ($k + 1) ?></td>
                                                                            <td></td>
                                                                            <td class="text-nowrap"><?= $item['props']['sku']['VALUE'] ?></td>
                                                                            <td><a target="_blank" href="<?= $item['url'] ?>"><?= $item['NAME'] ?></a></td>
                                                                            <td class="text-nowrap">
                                                                                <input
                                                                                    name="quantity"
                                                                                    type="number"
                                                                                    min="1"
                                                                                    max="<?= $item['count'] ?>"
                                                                                    class="form-control input-sm"
                                                                                    size="3" maxlength="3"
                                                                                    value="<?= $item['count'] ?>"
                                                                                    data-value="<?= $item['count'] ?>"
                                                                                >
                                                                            </td>
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
                            <? endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="shipment-menu" data-stick-block>
                        <div class="menu">
                            <ul class="fa-ul">
                                <li>
                                    <button data-store="<?= htmlspecialchars(json_encode($arResult['stores'][$store])) ?>" class="btn btn-primary" data-rtu-create>
                                        Отгрузить выбранное
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>

<? \Hogart\Lk\Helper\Template\Dialog::Start("add-rtu-dialog", [
    'dialog-options' => 'hashTracking: false, closeOnOutsideClick: false, closeOnEscape: false, closeOnConfirm: false',
    'title' => 'Отгрузить выбранное'
]) ?>
<form action="<?= $APPLICATION->GetCurPage(false) ?>" name="add-rtu" method="post">
    <? include __DIR__ . "/forms/add_rtu.php" ?>
    <input type="hidden" name="action" value="add_rtu">
</form>
<?
$id = \Hogart\Lk\Helper\Template\Dialog::$id;
$handler =<<<JS
    (function() {
      $('[data-remodal-id="$id"] form').submit();
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('confirmation', $handler);
\Hogart\Lk\Helper\Template\Dialog::End()
?>
