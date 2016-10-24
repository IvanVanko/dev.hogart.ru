<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Hogart\Lk\Entity\OrderRTUTable;
use Hogart\Lk\Entity\ContactTable;
?>
<div class="row spacer">
    <div class="col-sm-12">
        <fieldset>
            <div class="row spacer text-center">
                <div class="col-sm-6">
                    <div class="radio radio-primary radio-inline">
                        <input data-switch checked="checked" type="radio" name="delivery_type" value="<?= OrderRTUTable::DELIVERY_SELF ?>">
                        <label for="">
                            Самовывоз
                        </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="radio radio-primary radio-inline">
                        <input data-switch type="radio" name="delivery_type" value="<?= OrderRTUTable::DELIVERY_OUR ?>">
                        <label for="">
                            Доставка
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label data-delivery-type="<?= OrderRTUTable::DELIVERY_SELF ?>" class="active control-label">Планируемое время отгрузки</label>
                    <label data-delivery-type="<?= OrderRTUTable::DELIVERY_OUR ?>" class="control-label">Планируемое время доставки</label>
                </div>
            </div>
            <div class="row spacer">
                <div class="col-sm-3 form-group">
                    <input required type="text" name="plan_date" class="col-sm-3 form-control" placeholder="Дата" data-error="Дата должна быть заполнена">
                    <div class="help-block with-errors"></div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <select required name="plan_time" class="form-control selectpicker" title="Выберите один из интервалов">
                            <? foreach (OrderRTUTable::getIntervals() as $interval): ?>
                                <option value="<?= $interval ?>"><?= OrderRTUTable::getDateIntevalText($interval) ?></option>
                            <? endforeach; ?>
                            <div class="help-block with-errors"></div>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="row spacer">
    <div class="col-sm-12">
        <div class="active" data-delivery-type="<?= OrderRTUTable::DELIVERY_SELF ?>">
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label">Место отгрузки</label>
                </div>
            </div>
            <div class="row spacer">
                <div class="col-sm-6">
                    <span data-store-address></span>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input data-switch type="checkbox" name="is_cargo_space" value="1">
                        <label for="">
                            <abbr title="Товар будет собран в паллеты и упакован в пленку, - время на получение товара значительно сократится">Собрать в грузовые места</abbr>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div disabled="disabled" class="" data-delivery-type="<?= OrderRTUTable::DELIVERY_OUR ?>">
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label">Контактное лицо</label>
                    <label class="pull-right">
                        <div class="checkbox checkbox-primary checkbox-inline">
                            <input data-switch type="checkbox" name="new_contact" value="1">
                            <label for="">
                                Новый контакт
                            </label>
                        </div>
                    </label>
                </div>
            </div>
            <div class="row spacer">
                <div class="col-sm-12">
                    <fieldset data-new-contact="false">
                        <select name="contact" class="form-control selectpicker">
                        <? foreach ($arResult['contacts'] as $contact): ?>
                            <option data-owner-type="<?= $contact['owner_type'] ?>" data-owner="<?= $contact['owner_id'] ?>" value="<?= $contact['id'] ?>"><?= ContactTable::getFio($contact) ?></option>
                        <? endforeach; ?>
                        </select>
                    </fieldset>
                    <fieldset disabled="disabled" style="display: none" data-new-contact="true" class="form-group">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label">Фамилия Имя Отчество</label>
                                <div class="row spacer">
                                    <div class="form-group col-sm-4">
                                        <input name="new_last_name" required="required" type="text" class="col-sm-4 form-control" placeholder="Фамилия" data-error="Поле не должно быть пустым">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input name="new_name" required="required" type="text" class="col-sm-4 form-control" placeholder="Имя" data-error="Поле не должно быть пустым">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <input name="new_middle_name" type="text" class="col-sm-4 form-control" placeholder="Отчество">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label class="control-label">E-mail</label>
                                        <input name="new_email" type="email" class="form-control" placeholder="Email">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="control-label">Телефон (моб.)</label>
                                        <input name="new_phone[<?= \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_MOBILE ?>]" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label class="control-label">Телефон (гор.)</label>
                                        <input name="new_phone[<?= \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_STATIC ?>]" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="row vertical-align">
                <div class="col-sm-12 center-between">
                    <label class="control-label">Адрес доставки</label>
                    <label class="pull-right">
                        <div class="checkbox checkbox-primary checkbox-inline">
                            <input data-switch type="checkbox" name="new_address" value="1">
                            <label for="">
                                Новый адрес
                            </label>
                        </div>
                    </label>
                </div>
            </div>
            <div class="row spacer center-between">
                <div class="col-sm-6">
                    <fieldset data-new-address="false">
                        <select name="delivery_address" class="form-control selectpicker">
                            <? foreach ($arResult['addresses'] as $fias_code => $address): ?>
                                <option data-owner-type="<?= $address['owner_type'] ?>" data-owner="<?= $address['owner_id'] ?>" value="<?= $address['guid_id'] ?>"><?= $address['value'] ?></option>
                            <? endforeach; ?>
                        </select>
                    </fieldset>
                    <fieldset disabled="disabled" style="display: none" data-new-address="true" class="form-group">
                        <input required data-suggest="address" name="address" type="text" class="form-control" placeholder="Введите адрес в свободной форме" data-error="Поле не должно быть пустым">
                        <div class="help-block with-errors"></div>
                    </fieldset>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="checkbox checkbox-primary checkbox-inline">
                        <input data-switch type="checkbox" name="is_tk" value="1">
                        <label for="">
                            Транспортная компания
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <fieldset data-tk disabled="disabled" style="display: none;">
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label">Наименование транспортной компании</label>
                            </div>
                        </div>
                        <div class="row spacer">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="tk_name" placeholder="Наименование">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label">Адрес транспортной компании</label>
                            </div>
                        </div>
                        <div class="row spacer">
                            <div class="col-sm-12 form-group">
                                <input required data-suggest="address" name="tk_address" type="text" class="form-control" placeholder="Введите адрес в свободной форме" data-error="Поле не должно быть пустым">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <fieldset>
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label">Телефон</label>
                        </div>
                    </div>
                    <div class="row spacer vertical-align">
                        <div class="col-sm-6">
                            <input name="phone" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
                        </div>
                        <div class="col-sm-6 text-right">
                            <div class="checkbox checkbox-primary checkbox-inline">
                                <input data-switch type="checkbox" name="is_sms_notify" value="1">
                                <label for="">
                                    Оповещать по смс
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label">E-mail</label>
                        </div>
                    </div>
                    <div class="row spacer vertical-align">
                        <div class="col-sm-6">
                            <input name="email" type="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-sm-6 text-right">
                            <div class="checkbox checkbox-primary checkbox-inline">
                                <input data-switch type="checkbox" name="is_email_notify" value="1">
                                <label for="">
                                    Оповещать по E-mail
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<input type="hidden" name="rows" value="">