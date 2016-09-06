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
            <input required="required" data-suggest="address" name="address[<?= AddressTypeTable::TYPE_LEGAL ?>]" type="text" class="form-control" placeholder="Введите адрес в свободной форме" data-error="Поле не должно быть пустым">
            <div class="help-block with-errors"></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <label class="control-label">Фактический адрес</label>
        </div>
        <div class="col-sm-6 pull-right text-right">
            <label class="checkbox-inline">
                <input checked="checked" onclick="document.getElementById('actual_address').disabled=this.checked;" type="checkbox" name="actual_address_as_legal" value="1"> Совпадает с юридическим адресом
            </label>
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-12">
            <input data-suggest="address" disabled="disabled" id="actual_address" name="address[<?= AddressTypeTable::TYPE_ACTUAL ?>]" type="text" class="form-control" placeholder="Введите адрес в свободной форме">
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <label class="control-label">Контактные данные</label>
        </div>
    </div>
    <div class="row spacer">
        <div class="col-sm-6">
            <div class="row spacer vertical-align" data-contact-email>
                <div class="col-sm-8">
                    <input name="email[]" type="email" class="form-control" placeholder="Email">
                </div>
                <div class="col-sm-4">
                    <a role="button" class="btn btn-primary btn-xs" data-cloner href="javascript:void(0)" title="Добавить e-mail">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    <a role="button" class="btn btn-danger btn-xs" data-remover href="javascript:void(0)" onclick="$(this).parents('[data-contact-email]').remove()" title="Удалить e-mail">
                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row spacer vertical-align" data-contact-phone>
                <div class="col-sm-8">
                    <input name="phone[<?= ContactInfoTable::PHONE_KIND_STATIC ?>][]" type="text" data-mask="+7 (999) 999-99-99" class="form-control" placeholder="Телефон">
                </div>
                <div class="col-sm-4">
                    <a role="button" class="btn btn-primary btn-xs" data-cloner href="javascript:void(0)" title="Добавить телефон">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    <a role="button" class="btn btn-danger btn-xs" data-remover href="javascript:void(0)" onclick="$(this).parents('[data-contact-phone]').remove()" title="Удалить телефон">
                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <label class="control-label">Генеральный директор</label>
        </div>
    </div>
    <div class="row spacer">
        <div class="form-group col-sm-4">
            <input name="director_last_name" required="required" type="text" class="col-sm-4 form-control" placeholder="Фамилия" data-error="Поле не должно быть пустым">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group col-sm-4">
            <input name="director_name" required="required" type="text" class="col-sm-4 form-control" placeholder="Имя" data-error="Поле не должно быть пустым">
            <div class="help-block with-errors"></div>
        </div>
        <div class="form-group col-sm-4">
            <input name="director_middle_name" type="text" class="col-sm-4 form-control" placeholder="Отчество">
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label">Реквизиты счета</label>
        </div>
    </div>
    <div class="row spacer" data-payment-account>
        <div class="col-sm-12">
            <div class="row spacer vertical-align">
                <div class="col-sm-8 form-group">
                    <input required data-suggest="bank" name="payment_account[bik][]" type="text" class="form-control" placeholder="Название банка, БИК или SWIFT" data-error="Поле не должно быть пустым">
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-4 pull-right text-right">
                    <label class="checkbox-inline">
                        <input checked="checked" type="checkbox" name="payment_account[is_main][]" value="1"> Сделать основным
                    </label>
                </div>
            </div>
            <div class="row vertical-align">
                <div class="col-sm-8 form-group">
                    <input required data-mask="99999 999 9999 9999 9999" name="payment_account[number][]" type="text" class="form-control" placeholder="Расчетный счет" data-error="Поле не должно быть пустым">
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-4">
                    <a role="button" class="btn btn-primary btn-xs" data-cloner href="javascript:void(0)" title="Добавить счет">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

</fieldset>