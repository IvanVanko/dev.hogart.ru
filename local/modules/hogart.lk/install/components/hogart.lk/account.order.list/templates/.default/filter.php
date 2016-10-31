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
 * @var array $state
 * @var ViewNode $ordersNode
 */

use Hogart\Lk\Entity\OrderTable;
use Hogart\Lk\Helper\Template\ViewNode;
?>
<form action="<?= $APPLICATION->GetCurPage(false) ?>"  name="filter" method="get">
    <input type="hidden" name="action" value="filter">
    <input type="hidden" name="<?= BX_AJAX_PARAM_ID ?>" value="<?= $ordersNode->getId() ?>">
    <div class="row">
        <div class="col-sm-12">
            <label>Период заказов</label>
            <div class="form-group form-group-sm center-between">
                <input type="text" name="date_from" class="form-control input-sm" value="<?= $_REQUEST['date_from'] ?>" placeholder="с">
                <span style="padding: 0 5px;">&mdash;</span>
                <input type="text" name="date_to" class="form-control input-sm" value="<?= $_REQUEST['date_to'] ?>" placeholder="по">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label>Номер заказа</label>
            <div class="form-group">
                <input type="text" name="number" class="form-control input-sm" value="<?= $_REQUEST['number']?>" placeholder="Введите номер">
            </div>
        </div>
    </div>
    <? if ($arParams['STATE'] == OrderTable::STATE_NORMAL): ?>
    <div class="row">
        <div class="col-sm-12">
            <label>Статус</label>
            <div class="form-group">

                <? foreach ([OrderTable::STATUS_NEW, OrderTable::STATUS_IN_WORK] as $status): ?>
                <div class="checkbox checkbox-primary">
                    <input <?= (in_array($status, $_REQUEST['status']) ? 'checked="checked"' : '') ?> type="checkbox" name="status[]" value="<?= $status ?>">
                    <label>
                        <?= OrderTable::getStatusText($status) ?>
                    </label>
                </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
    <? endif; ?>
    <? if (!empty($arResult['companies'])): ?>
    <div class="row">
        <div class="col-sm-12">
            <label>Контрагент</label>
            <div class="form-group">
                <? foreach ($arResult['companies'] as $company): ?>
                    <div class="checkbox checkbox-primary">
                        <input <?= (in_array($company['co_id'], $_REQUEST['company']) ? 'checked="checked"' : '') ?> type="checkbox" name="company[]" value="<?= $company['co_id'] ?>">
                        <label>
                            <?= $company['co_name'] ?>
                        </label>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
    <? endif; ?>
    <? if (!empty($arResult['stores'])): ?>
    <div class="row">
        <div class="col-sm-12">
            <label>Склад</label>
            <div class="form-group">
                <? foreach ($arResult['stores'] as $store): ?>
                    <div class="checkbox checkbox-primary">
                        <input <?= (in_array($store['s_XML_ID'], $_REQUEST['store']) ? 'checked="checked"' : '') ?> type="checkbox" name="store[]" value="<?= $store['s_XML_ID'] ?>">
                        <label>
                            <?= $store['s_TITLE'] ?>
                        </label>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
    <? endif; ?>
    <div class="row">
        <div class="col-sm-12">
            <label>Тип заказа</label>
            <div class="form-group">
            <? foreach ([OrderTable::TYPE_SALE, OrderTable::TYPE_PROMO] as $type): ?>
                <div class="checkbox checkbox-primary">
                    <input <?= (in_array($type, $_REQUEST['type']) ? 'checked="checked"' : '') ?> type="checkbox" name="type[]" value="<?= $type ?>">
                    <label>
                        <?= OrderTable::getTypeText($type) ?>
                    </label>
                </div>
            <? endforeach; ?>
            </div>
        </div>
    </div>
    <input role="button" class="btn btn-default" type="reset" value="Сбросить">
    <input role="button" class="btn btn-primary" type="submit" value="Отфильтровать">
</form>