<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/01/2017
 * Time: 17:21
 *
 * @global $APPLICATION
 */
use Hogart\Lk\Entity\ReportTable;
?>
<form action="<?= $APPLICATION->GetCurPage(false) ?>"  name="filter" method="get">
    <input type="hidden" name="action" value="filter">
    <input type="hidden" name="<?= BX_AJAX_PARAM_ID ?>" value="<?= $reportsNode->getId() ?>">
    <div class="row">
        <div class="col-sm-12">
            <label>Период отчетов</label>
            <div class="form-group form-group-sm center-between">
                <input type="text" name="date_from" class="form-control input-sm" value="<?= $_REQUEST['date_from'] ?>" placeholder="с">
                <span style="padding: 0 5px;">&mdash;</span>
                <input type="text" name="date_to" class="form-control input-sm" value="<?= $_REQUEST['date_to'] ?>" placeholder="по">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label>Тип отчета</label>
            <div class="form-group">
                <? foreach ([ReportTable::TYPE_PRICE, ReportTable::TYPE_STOCK] as $type): ?>
                    <div class="checkbox checkbox-primary">
                        <input <?= (in_array($type, $_REQUEST['type']) ? 'checked="checked"' : '') ?> type="checkbox" name="type[]" value="<?= $type ?>">
                        <label>
                            <?= ReportTable::getTypeText($type) ?>
                        </label>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </div>
    <input role="button" class="btn btn-default" type="reset" value="Сбросить">
    <input role="button" class="btn btn-primary" type="submit" value="Отфильтровать">
</form>