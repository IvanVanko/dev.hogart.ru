<div class="row">
    <div class="col-sm-12">
        <label class="control-label">Реквизиты счета</label>
    </div>
</div>
<div class="row spacer">
    <div class="col-sm-12">
        <div class="row spacer" data-payment-account>
            <div class="col-sm-12">
                <div class="row spacer vertical-align">
                    <div class="col-sm-6 form-group">
                        <input required data-suggest="bank" name="payment_account[bik]" type="text" class="form-control" placeholder="Название банка, БИК или SWIFT" data-error="Поле не должно быть пустым">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="col-sm-6 pull-right text-right">
                        <div class="checkbox checkbox-primary checkbox-inline">
                            <input tabindex="-1" data-switch checked="checked" type="checkbox" name="payment_account[is_main]" value="1">
                            <label>
                                Основной
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row vertical-align">
                    <div class="col-sm-8 form-group">
                        <input required data-mask="99999 999 9999 9999 9999" name="payment_account[number]" type="text" class="form-control" placeholder="Расчетный счет" data-error="Поле не должно быть пустым">
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="col-sm-4">
                        <a tabindex="-1" role="button" class="btn btn-primary btn-xs" data-cloner href="javascript:void(0)" title="Добавить счет">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>
                        <a tabindex="-1" role="button" class="btn btn-danger btn-xs" data-remover href="javascript:void(0)" onclick="$(this).parents('[data-payment-account]').remove()" title="Удалить счет">
                            <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
