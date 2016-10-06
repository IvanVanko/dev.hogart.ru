<?php
/**
 * @var array $arResult
 */
?>
<input type="hidden" name="type" value="<?= $arResult['current_company']['type'] ?>">

<? /*
<div class="row spacer">
    <div class="col-sm-12 form-group" style="position: relative">
        <label class="control-label">Выберите компанию Хогарт</label>
        <select name="hogart_company" class="form-control selectpicker">
            <? foreach ($arResult['hogart_companies'] as $h_company): ?>
                <option value="<?= $h_company['id'] ?>"><?= $h_company['name'] ?></option>
            <? endforeach; ?>
        </select>
    </div>
</div>
*/ ?>

<div class="row spacer">
    <div class="col-sm-12 form-group" style="position: relative">
        <label class="control-label">Выберите валюту договора</label>
        <select name="currency" class="form-control selectpicker">
        <? foreach ($arResult['currency'] as $currency): ?>
            <option <?= ($currency['BASE'] == 'Y' ? 'selected' : '') ?> value="<?= $currency['CURRENCY'] ?>"><?= ($currency['CURRENCY'] . ($currency['LANG_FULL_NAME'] != '' ? " ({$currency['LANG_FULL_NAME']})" : "")) ?></option>
        <? endforeach; ?>
        </select>
    </div>
</div>
<div class="row spacer bottom-between">
    <div class="col-sm-4 form-group">
        <div class="row">
            <div class="col-sm-12 text-center">
                <label class="control-label">
                    Оплата безналичным расчетом
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <input
                    <?= (($arResult['current_company']['type'] == \Hogart\Lk\Entity\CompanyTable::TYPE_INDIVIDUAL) ? "checked readonly" : "") ?>
                    name="perm_clearing"
                    type="checkbox"
                    value="1"
                    data-switch>
            </div>
        </div>
    </div>
    <div class="col-sm-4 form-group">
        <div class="row">
            <div class="col-sm-12 text-center">
                <label class="control-label">
                    Оплата наличными
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <input
                    <?= (($arResult['current_company']['type'] == \Hogart\Lk\Entity\CompanyTable::TYPE_INDIVIDUAL) ? "checked readonly" : "") ?>
                    name="perm_cash"
                    type="checkbox"
                    value="1"
                    data-switch>
            </div>
        </div>
    </div>
    <div class="col-sm-4 form-group">
        <div class="row">
            <div class="col-sm-12 text-center">
                <label class="control-label">
                    Оплата банковской картой или e-money
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <input
                    <?= (($arResult['current_company']['type'] == \Hogart\Lk\Entity\CompanyTable::TYPE_LEGAL_ENTITY) ? "" : "checked") ?>
                    readonly
                    name="perm_card"
                    type="checkbox"
                    value="1"
                    data-switch>
            </div>
        </div>
    </div>
</div>
<div class="row spacer">
    <div class="col-sm-4 form-group">
        <div class="row">
            <div class="col-sm-12 text-center">
                <label class="control-label">
                    Разрешено оформление товаров
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <input
                    checked
                    name="perm_item"
                    type="checkbox"
                    value="1"
                    data-switch>
            </div>
        </div>
    </div>
    <div class="col-sm-offset-4 col-sm-4 form-group">
        <div class="row">
            <div class="col-sm-12 text-center">
                <label class="control-label">
                    Разрешено оформление рекламной продукции
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <input
                    <?= ($arResult['account']['is_promo_accesss'] ? "" : "readonly") ?>
                    name="perm_promo"
                    type="checkbox"
                    value="1"
                    data-switch>
            </div>
        </div>
    </div>
</div>