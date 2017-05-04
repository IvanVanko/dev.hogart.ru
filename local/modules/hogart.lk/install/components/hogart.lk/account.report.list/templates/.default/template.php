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
        <form action="<?= $APPLICATION->GetCurPage(false) ?>" name="new-report" method="post">
            <input type="hidden" name="action" value="new-report">
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

            <div id="companies">
                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label">Компания(-ии)</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <select class="form-control selectpicker" name="company[]" id="company" title="Все" data-live-search="true" multiple>
                            <? foreach($arResult['companies'] as $company): ?>
                                <option <?= $company['selected'] ?> value="<?= $company['id']?>"><?= $company['name']; ?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
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
                        <input data-switch="" type="checkbox" name="warehouse" value="1">
                        <label>
                            Складская программа
                        </label>
                    </div>
                </div>
            </div>

            <div class="row spacer-20">
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
                <div class="col-sm-12">
                    <label class="control-label">Группировка</label>
                </div>
            </div>
            <div class="row groups">
                <div class="col-xs-6 col-sm-5">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h6>Доступные группировки</h6>
                        </div>
                    </div>
                    <ul class="h6 list-group" id="available-group">
                        <li data-id="category" class="list-group-item">
                            <i class="fa fa-arrows"></i>
                            По категории
                        </li>
                        <li data-id="brand" class="list-group-item">
                            <i class="fa fa-arrows"></i>
                            По бренду
                        </li>
                    </ul>
                </div>
                <div class="hidden-xs col-sm-2 text-center">
                    <h3><i class="fa fa-arrows-h"></i></h3>
                </div>
                <div class="col-xs-6 col-sm-5">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <h6>Группировать по</h6>
                        </div>
                    </div>
                    <ul class="h6 list-group" id="enabled-group"></ul>
                </div>
                <input type="hidden" name="groups" value="">
            </div>
            <div class="row">
                <div class="col-sm-6 pull-right text-right">
                    <input type="submit" class="btn btn-primary" value="Сформировать">
                </div>
            </div>
        </form>
    </div>
    <div class="delimiter visible-xs    "></div>
    <div class="col-sm-3 order-filter aside">
        <h4>Последние 5 отчетов</h4>
        <? if (empty($arResult['reports'])): ?>
            <h5>Ранее отчеты не формировались</h5>
        <? endif; ?>
        <? foreach ($arResult['reports'] as $id => $report): ?>
            <div class="row spacer report-line" data-report-id="<?= $id ?>">
                <div class="col-sm-12">
                    <h5>
                        <a href="/account/reports/get/<?= $report['account_id'] ?>/<?= $id ?>">
                            <span class="title">
                                <?= ReportTable::getTitle($id) ?>
                            </span>
                            <br>
                            <i class="fa fa-download"></i>
                            <span class="h6 color-green">
                                — .<?= pathinfo($report['path'], PATHINFO_EXTENSION) ?>
                                , <?= round(filesize($report['path']) / 1048576, 2) ?> mb
                            </span>
                        </a>
                    </h5>
                </div>
            </div>
        <? endforeach; ?>

    </div>
</div>

<? Dialog::Start('add-queue', [
    'dialog-options' => 'hashTracking: false, closeOnOutsideClick: false, closeOnEscape: false, closeOnConfirm: false',
    'title' => 'Новый отчет'
])?>

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
