<?php
use Hogart\Lk\Entity\ContactInfoTable;
?>
<div class="row">
    <div class="col-sm-6">
        <label class="control-label">Контактные данные</label>
    </div>
</div>
<div class="row spacer">
    <div class="col-sm-6">
        <div class="row spacer vertical-align" data-contact-email>
            <div class="col-sm-8">
                <input name="email" type="email" class="form-control" placeholder="Email">
            </div>
            <div class="col-sm-4">
                <a tabindex="-1" role="button" class="btn btn-primary btn-xs" data-cloner href="javascript:void(0)" title="Добавить e-mail">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
                <a tabindex="-1" role="button" class="btn btn-danger btn-xs" data-remover href="javascript:void(0)" onclick="$(this).parents('[data-contact-email]').remove()" title="Удалить e-mail">
                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row spacer vertical-align" data-contact-phone>
            <div class="col-sm-8">
                <input name="phone[<?= ContactInfoTable::PHONE_KIND_STATIC ?>]" type="text" data-mask="+7 (999) 999-99-99" class="form-control" placeholder="Телефон">
            </div>
            <div class="col-sm-4">
                <a tabindex="-1" role="button" class="btn btn-primary btn-xs" data-cloner href="javascript:void(0)" title="Добавить телефон">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
                <a tabindex="-1" role="button" class="btn btn-danger btn-xs" data-remover href="javascript:void(0)" onclick="$(this).parents('[data-contact-phone]').remove()" title="Удалить телефон">
                    <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
    </div>
</div>