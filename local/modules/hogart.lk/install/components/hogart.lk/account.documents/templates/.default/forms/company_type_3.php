<?php
/**
 * @var array $company
 */
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\CompanyTable;
?>
<fieldset name="company_type_<?= CompanyTable::TYPE_INDIVIDUAL ?>">
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label">Фамилия Имя Отчество</label>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-sm-4">
            <input name="last_name" required="required" type="text" class="col-sm-4 form-control" placeholder="Фамилия" data-error="Поле не должно быть пустым">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group col-sm-4">
            <input name="name" required="required" type="text" class="col-sm-4 form-control" placeholder="Имя" data-error="Поле не должно быть пустым">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group col-sm-4">
            <input name="middle_name" type="text" class="col-sm-4 form-control" placeholder="Отчество">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <label class="control-label">Адрес прописки</label>
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12">
            <input data-bind="addresses[<?= AddressTypeTable::TYPE_LEGAL?>][0].value" data-suggest="address" name="address[<?= AddressTypeTable::TYPE_LEGAL?>]" type="text" class="form-control" placeholder="Введите адрес в свободной форме">
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12 center-between">
            <label class="control-label">Адрес проживания</label>
            <div class="pull-right text-right">
                <div class="checkbox checkbox-primary checkbox-inline">
                    <input data-switch checked="checked" onchange="document.getElementById('residential_address_<?= CompanyTable::TYPE_INDIVIDUAL ?>').disabled=this.checked;" type="checkbox" name="residential_address_as_actual" value="1">
                    <label>
                        Совпадает с адресом прописки
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12">
            <input data-suggest="address" data-bind="addresses[<?= AddressTypeTable::TYPE_ACTUAL?>][0].value" disabled="disabled" id="residential_address_<?= CompanyTable::TYPE_INDIVIDUAL ?>" name="address[<?= AddressTypeTable::TYPE_ACTUAL?>]" type="text" class="form-control" placeholder="Введите адрес в свободной форме">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-group">
            <label class="control-label">Дата регистрации по месту жительства</label>
            <input name="date_fact_address" type="text" class="form-control" data-mask="99.99.9999" placeholder="ДД.ММ.ГГГГ">
        </div>
    </div>
    <? if (empty($edit_company['id'])): ?>
    <? include __DIR__ . "/company_contacts.php"; ?>
    <? endif; ?>
    <? include __DIR__ . "/doc_pass.php"; ?>
</fieldset>
