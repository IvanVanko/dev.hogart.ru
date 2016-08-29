<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 18.08.2016 16:27
 */
use Hogart\Lk\Entity\AddressTypeTable;
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
/** @ver array $companies */
$this->setFrameMode(true);
ini_set("xdebug.var_display_max_depth", -1);
//var_dump($arResult);
?>

<div class="row">
    <div class="col-sm-12 col-xs-12">
        <h3>Юридические лица и договора</h3>
    </div>
    <div class="col-sm-12 col-xs-12 authinfo">
        <form method="post">
            <input type="hidden" name="action" id="haction">
        <div class="row spacer form-group">
            <div class="col-sm-10 col-xs-10">
                <select class="form-control" name="cc_id" id="current_company">
                    <? foreach($arResult['companies'] as $company): ?>
                        <option value="<?= $company['COMPANY_id']?>" <?= $company['selected']?>><?= $company['COMPANY_name']; ?></option>
                    <? endforeach; ?>
                </select>
            </div>
            <div class="col-sm-2 col-xs-2">
                <?= \Hogart\Lk\Helper\Template\Dialog::Link('edit-company-dialog', 'edit', 'btn btn-default')?>
                <?= \Hogart\Lk\Helper\Template\Dialog::Link('fav-company', 'fav', 'btn btn-'.($arResult['current_company']['is_favorite'] ? 'primary' : 'default' ))?>
            </div>
        </div>
        </form>
        <div class="row spacer">
            <div class="col-sm-12 col-xs-12">
                <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-company-fiz-dialog', 'Добавить Физ. лицо', 'btn btn-primary')?>
                <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-company-ip-dialog', 'Добавить ИП', 'btn btn-primary')?>
                <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-company-ooo-dialog', 'Добавить Юр. лицо', 'btn btn-primary')?>
            </div>
        </div>
    </div>
</div>
<? \Hogart\Lk\Helper\Template\Dialog::Start("add-company-fiz-dialog", [
    'dialog-options' => 'closeOnConfirm: false',
    'title' => 'Добавление физического лица'
]) ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="add-company-fiz" method="post">
    <input type="hidden" name="action" value="add-company-fiz">
    <div class="form-group">
        <input name="last_name" required="required" type="text" class="form-control" placeholder="Фамилия" data-error="Поле не должно быть пустым">
        <input name="name" required="required" type="text" class="form-control" placeholder="Имя" data-error="Поле не должно быть пустым">
        <input name="middle_name" type="text" class="form-control" placeholder="Отчество">
    </div>
    <div class="form-group">
        <label class="control-label">Адрес прописки</label>
        <input name="postal_code[<?= AddressTypeTable::TYPE_ACTUAL?>]" type="text" class="form-control" placeholder="Индекс">
        <input name="city[<?= AddressTypeTable::TYPE_ACTUAL?>]" type="text" class="form-control" placeholder="Город">
        <input name="street[<?= AddressTypeTable::TYPE_ACTUAL?>]" type="text" class="form-control" placeholder="Улица">
        <input name="house[<?= AddressTypeTable::TYPE_ACTUAL?>]" type="text" class="form-control" placeholder="Дом">
        <input name="flat[<?= AddressTypeTable::TYPE_ACTUAL?>]" type="text" class="form-control" placeholder="Квартира">
    </div><div class="form-group">
        <label class="control-label">Адрес проживания</label>
        <input name="postal_code[<?= AddressTypeTable::TYPE_RESIDENTIAL?>]" type="text" class="form-control" placeholder="Индекс">
        <input name="city[<?= AddressTypeTable::TYPE_RESIDENTIAL?>]" type="text" class="form-control" placeholder="Город">
        <input name="street[<?= AddressTypeTable::TYPE_RESIDENTIAL?>]" type="text" class="form-control" placeholder="Улица">
        <input name="house[<?= AddressTypeTable::TYPE_RESIDENTIAL?>]" type="text" class="form-control" placeholder="Дом">
        <input name="flat[<?= AddressTypeTable::TYPE_RESIDENTIAL?>]" type="text" class="form-control" placeholder="Квартира">
    </div>
    <div class="form-group">
        <label class="control-label">Телефон (моб.)</label>
        <input name="phone[<?= \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_MOBILE ?>]" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
    </div>
    <div class="form-group">
        <label class="control-label">Телефон (моб.)</label>
        <input name="phone[<?= \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_MOBILE ?>]" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
    </div>
    <div class="form-group">
        <label class="control-label">Email</label>
        <input name="email" type="email" class="form-control" placeholder="Email">
    </div>
    <div class="form-group">
        <label class="control-label">Дата регистрации по месту жительства</label>
        <input name="date_fact_address" type="text" class="form-control" placeholder="ДД/ММ/ГГГГ">
    </div>
    <div class="form-group">
        <label class="control-label">Вид документа удостоверяющего личность</label>
        <select name="doc_pass" class="form-control">
            <option value="<?= \Hogart\Lk\Entity\CompanyTable::DOC_EMPTY?>">пустой</option>
            <option value="<?= \Hogart\Lk\Entity\CompanyTable::DOC_PASSPORT?>">пасспорт</option>
            <option value="<?= \Hogart\Lk\Entity\CompanyTable::DOC_NO_PASSPORT?>">другое</option>
        </select>
        <input name="doc_serial" type="text" class="form-control" placeholder="Серия">
        <input name="doc_number" type="text" class="form-control" placeholder="Номер">
        <input name="doc_ufms" type="text" class="form-control" placeholder="Кем выдан">
        <input name="doc_date" type="text" class="form-control" placeholder="ДД/ММ/ГГГГ">
    </div>
    <input type="hidden" name="action" value="add-company-fiz">
</form>
<?
$id = \Hogart\Lk\Helper\Template\Dialog::$id;
$handler =<<<JS
    (function() {
      $('[data-remodal-id="$id"] form').validator();
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('opening', $handler);
$handler =<<<JS
    (function() {
      $('[data-remodal-id="$id"] form').submit();
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('confirmation', $handler);
\Hogart\Lk\Helper\Template\Dialog::End()
?>

<script lang="Javascript">
    $(function() {
        $('#current_company').change(function() {
            $('#haction').val('change_company');
            this.form.submit();
        });
        $('#edit_company').click(function() {
            $('#haction').val('edit_company');
            this.form.submit();
        });
    });
</script>
