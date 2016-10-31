<?php
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Entity\CompanyTable;
?>

<fieldset name="company_type_<?= CompanyTable::TYPE_LEGAL_ENTITY ?>">
    <div class="row spacer">
        <div class="col-sm-12 form-group">
            <input
                required="required"
                data-error="Поле не должно быть пустым"
                data-suggest="party"
                data-suggest-params='{"status":["ACTIVE"],"type":"LEGAL"}'
                data-suggest-onselect="partySelect"
                name="name" type="text" class="col-sm-12 form-control" placeholder="Введите название, адрес, ИНН или ОГРН">
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 form-group">
            <input required="required"
                   data-error="Поле не должно быть пустым"
                   name="inn" type="text" class="form-control" placeholder="ИНН">
            <div class="help-block with-errors"></div>
        </div>
        <div class="col-sm-6 form-group">
            <input required="required"
                   data-error="Поле не должно быть пустым"
                   name="kpp" type="text" class="form-control" placeholder="КПП">
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label">Юридический адрес</label>
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12 form-group">
            <input required="required" data-bind="addresses[<?= AddressTypeTable::TYPE_LEGAL?>][0].value" data-suggest="address" name="address[<?= AddressTypeTable::TYPE_LEGAL ?>]" type="text" class="form-control" placeholder="Введите адрес в свободной форме" data-error="Поле не должно быть пустым">
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="row spacer">
        <div class="col-sm-12 center-between">
            <label class="control-label">Фактический адрес</label>
            <div class="pull-right text-right">
                <div class="checkbox checkbox-primary checkbox-inline">
                    <input data-switch checked="checked" onchange="document.getElementById('actual_address_<?= CompanyTable::TYPE_LEGAL_ENTITY ?><?= (!empty($edit_company['id']) ? "_" . $edit_company['id'] : "") ?>').disabled=this.checked;" type="checkbox" name="actual_address_as_legal" value="1">
                    <label>
                        Совпадает с юридическим адресом
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12">
            <input data-suggest="address" data-bind="addresses[<?= AddressTypeTable::TYPE_ACTUAL?>][0].value" disabled="disabled" id="actual_address_<?= CompanyTable::TYPE_LEGAL_ENTITY ?><?= (!empty($edit_company['id']) ? "_" . $edit_company['id'] : "") ?>" name="address[<?= AddressTypeTable::TYPE_ACTUAL ?>]" type="text" class="form-control" placeholder="Введите адрес в свободной форме">
        </div>
    </div>
    <? if (empty($edit_company['id'])): ?>
    <? include __DIR__ . "/company_contacts.php"; ?>
    <? endif; ?>
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label">Генеральный директор</label>
        </div>
    </div>
    <div class="row spacer">
        <div class="form-group col-sm-4">
            <input data-bind="chief_last_name" name="director_last_name" required="required" type="text" class="col-sm-4 form-control" placeholder="Фамилия" data-error="Поле не должно быть пустым">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group col-sm-4">
            <input data-bind="chief_name" name="director_name" required="required" type="text" class="col-sm-4 form-control" placeholder="Имя" data-error="Поле не должно быть пустым">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group col-sm-4">
            <input data-bind="chief_middle_name" name="director_middle_name" type="text" class="col-sm-4 form-control" placeholder="Отчество">
        </div>
    </div>
    <? include __DIR__ . "/payment_account.php"; ?>
</fieldset>