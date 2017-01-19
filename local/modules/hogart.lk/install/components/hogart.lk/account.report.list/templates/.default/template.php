<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 17/01/2017
 * Time: 15:26
 *
 * @global $APPLICATION
 * @var array $arResult
 * @var CBitrixComponent $component
 */

use Hogart\Lk\Helper\Template\Ajax;
use Hogart\Lk\Helper\Template\Dialog;
use Hogart\Lk\Entity\ReportTable;

?>

<div class="row">
    <div class="col-sm-9" id="reports-list">
        <? $reportsNode = Ajax::Start($component); ?>
        <? foreach ($arResult['reports'] as $id => $report): ?>
            <div class="row spacer report-line" data-report-id="<?= $id ?>">
                <div class="col-sm-12">
                    <h4>
                        <a href="/account/reports/get/<?= $report['account_id'] ?>/<?= $id ?>">
                            <span class="title">
                                <?= ReportTable::getTitle($id) ?>
                            </span>
                            <i class="fa fa-download"></i>
                        </a>
                    </h4>
                </div>
            </div>
            <div class="row spacer">
                <div class="col-sm-12">
                    <div class="delimiter color-green"></div>
                </div>
            </div>
        <? endforeach; ?>
        <? Ajax::End($reportsNode->getId()); ?>
    </div>
    <div class="col-sm-3 order-filter aside">
        <?= Dialog::Button('add-queue', 'Новый отчет', 'btn btn-primary', 'id="add-report"') ?>
        <? include __DIR__ . "/filter.php" ?>
    </div>
</div>

<? Dialog::Start('add-queue', [
    'dialog-options' => 'hashTracking: false, closeOnOutsideClick: false, closeOnEscape: false, closeOnConfirm: false',
    'title' => 'Новый отчет'
])?>
<form action="<?= $APPLICATION->GetCurPage(false) ?>" name="new-report" method="post">
    <input type="hidden" name="action" value="new-report">

    <div style="text-align: left">

        <div class="row">
            <div class="col-sm-12 text-center">
                <h4>Отбор</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <label class="control-label">Тип отчета</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form-group">
                <select class="form-control selectpicker" name="report" id="report">
                    <? foreach ([ReportTable::TYPE_PRICE, ReportTable::TYPE_STOCK] as $type): ?>
                        <option value="<?= $type ?>"><?= ReportTable::getTypeText($type) ?></option>
                    <? endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <label class="control-label">Компания(-ии)</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form-group">
                <select class="form-control selectpicker" name="company[]" id="company" title="Все" multiple>
                    <? foreach($arResult['companies'] as $company): ?>
                        <option value="<?= $company['id']?>"><?= $company['name']; ?></option>
                    <? endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <label class="control-label">Категории</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form-group">
                <select class="form-control" name="category[]" id="category" multiple>
                    <? foreach ($arResult['categories'] as $category): ?>
                        <option value="<?= $category['ID'] ?>" data-section="<?= $category['PATH'] ?>"><?= $category['NAME'] ?></option>
                    <? endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <label class="control-label">Бренды</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 form-group">
                <select class="form-control selectpicker" name="brand[]" id="brand" title="Все" data-live-search="true" multiple>
                    <? foreach($arResult['brands'] as $brand): ?>
                        <option value="<?= $brand['ID']?>"><?= $brand['NAME']; ?></option>
                    <? endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="checkbox checkbox-primary checkbox-inline">
                    <input data-switch="" type="checkbox" name="in_stock" value="1">
                    <label>
                        Наличие
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="checkbox checkbox-primary checkbox-inline">
                    <input data-switch="" type="checkbox" name="stock_program" value="1">
                    <label>
                        Складская программа
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="checkbox checkbox-primary checkbox-inline">
                    <input data-switch="" type="checkbox" name="sale" value="1">
                    <label>
                        Распродажа
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="checkbox checkbox-primary checkbox-inline">
                    <input data-switch="" type="checkbox" name="image" value="1">
                    <label>
                        Выводить изображение
                    </label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <h4>Группировка</h4>
            </div>
        </div>

        <div class="row" style="display: flex">
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h6>Возможные поля для группировки</h6>
                    </div>
                </div>
                <ul style="height: 100%" class="list-group" id="available-group">
                    <li data-id="category" class="list-group-item">
                        По категории
                    </li>
                    <li data-id="brand" class="list-group-item">
                        По бренду
                    </li>
                </ul>
            </div>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h6>Активные поля для группировки</h6>
                    </div>
                </div>
                <ul style="height: 100%" class="list-group" id="enabled-group">
                </ul>
            </div>
            <input type="hidden" name="groups" value="">
        </div>
    </div>
</form>
<?
$id = Dialog::$id;
$handler =<<<JS
    (function() {
      $('[data-remodal-id="$id"] form').validator();
    })
JS;
Dialog::Event('opening', $handler);
$handler =<<<JS
    (function() {
      $('[data-remodal-id="$id"] form').submit();
    })
JS;
Dialog::Event('confirmation', $handler);
Dialog::End()
?>
