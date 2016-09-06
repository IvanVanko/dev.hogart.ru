<div class="row">
    <div class="col-sm-6">
        <label class="control-label">Адрес доставки</label>
    </div>
    <div class="col-sm-6 pull-right text-right">
        <label class="checkbox-inline">
            <input checked="checked" type="checkbox" name="is_active" value="1"> Активен
        </label>
    </div>
</div>
<div class="row spacer">
    <div class="col-sm-12 form-group">
        <input data-bind="value" required="required" data-suggest="address" name="address" type="text" class="form-control" placeholder="Введите адрес в свободной форме" data-error="Поле не должно быть пустым">
        <div class="help-block with-errors"></div>
    </div>
</div>