<?php
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\ContactInfoTable;
use Hogart\Lk\Entity\CompanyTable;
?>

<fieldset name="company_type_<?= CompanyTable::TYPE_INDIVIDUAL_ENTREPRENEUR ?>">
    <div class="row spacer">
        <div class="col-sm-12 form-group">
            <input
                required="required"
                data-error="Поле не должно быть пустым"
                data-suggest="party"
                data-suggest-params='{"status":["ACTIVE"],"type":"INDIVIDUAL"}'
                data-suggest-onselect="partyIndividualSelect"
                name="name" type="text" class="col-sm-12 form-control" placeholder="Введите Фамилию, ИНН или ОГРН">
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
                   name="registration_date" type="text" class="form-control" data-mask="99/99/9999" placeholder="ДД/ММ/ГГГГ">
            <div class="help-block with-errors"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label">Адрес прописки</label>
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12">
            <input data-suggest="address" name="address[<?= AddressTypeTable::TYPE_ACTUAL?>]" type="text" class="form-control" placeholder="Введите адрес в свободной форме">
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12 center-between">
            <label class="control-label">Адрес проживания</label>
            <div class="pull-right text-right">
                <label class="checkbox-inline">
                    Совпадает с адресом прописки <input data-switch checked="checked" onchange="document.getElementById('residential_address_<?= CompanyTable::TYPE_INDIVIDUAL_ENTREPRENEUR ?>').disabled=this.checked;" type="checkbox" name="residential_address_as_actual" value="1">
                </label>
            </div>
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12">
            <input data-suggest="address" disabled="disabled" id="residential_address_<?= CompanyTable::TYPE_INDIVIDUAL_ENTREPRENEUR ?>" name="address[<?= AddressTypeTable::TYPE_RESIDENTIAL?>]" type="text" class="form-control" placeholder="Введите адрес в свободной форме">
        </div>
    </div>

    <? include __DIR__ . "/company_contacts.php"; ?>
    <? include __DIR__ . "/doc_pass.php"; ?>
    <? include __DIR__ . "/payment_account.php"; ?>
</fieldset>
