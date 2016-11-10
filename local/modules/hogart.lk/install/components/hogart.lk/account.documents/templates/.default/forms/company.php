<?php
/**
 * @global $APPLICATION
 */
use Hogart\Lk\Entity\CompanyTable;
?>
<div class="row vertical-align">
    <div class="col-sm-8">
        <select name="company_type" class="form-control selectpicker">
            <option value="<?= CompanyTable::TYPE_LEGAL_ENTITY ?>">Юридическое лицо</option>
            <option value="<?= CompanyTable::TYPE_INDIVIDUAL_ENTREPRENEUR ?>">Индивидуальный предприниматель</option>
            <option value="<?= CompanyTable::TYPE_INDIVIDUAL ?>">Физическое лицо</option>
        </select>
    </div>
    <div class="col-sm-4 pull-right text-right">
        <div class="checkbox checkbox-primary checkbox-inline">
            <input data-switch checked="checked" type="checkbox" name="is_active" value="1">
            <label>
                Активно
            </label>
        </div>
    </div>
</div>
<? include (__DIR__ . "/company_type_" . CompanyTable::TYPE_LEGAL_ENTITY . ".php"); ?>
<? include (__DIR__ . "/company_type_" . CompanyTable::TYPE_INDIVIDUAL_ENTREPRENEUR . ".php"); ?>
<? include (__DIR__ . "/company_type_" . CompanyTable::TYPE_INDIVIDUAL . ".php"); ?>
