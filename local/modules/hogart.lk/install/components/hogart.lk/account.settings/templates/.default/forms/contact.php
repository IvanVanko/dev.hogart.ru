<label class="control-label">Фамилия Имя Отчество</label>
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
    <div class="form-group col-sm-4">
        <label class="control-label">E-mail</label>
        <input data-bind="info[<?= \Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL ?>][0].value" name="email" type="email" class="form-control" placeholder="Email">
    </div>
    <div class="form-group col-sm-4">
        <label class="control-label">Телефон (моб.)</label>
        <input data-bind="info[<?= \Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE ?>][0].value" name="phone[<?= \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_MOBILE ?>]" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
    </div>
    <div class="form-group col-sm-4">
        <label class="control-label">Телефон (гор.)</label>
        <input data-bind="info[<?= \Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE ?>][1].value" name="phone[<?= \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_STATIC ?>]" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
    </div>
</div>