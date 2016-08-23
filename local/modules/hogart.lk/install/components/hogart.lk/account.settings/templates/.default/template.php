<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
$this->setFrameMode(true);
//ini_set("xdebug.var_display_max_depth", -1);
//var_dump($arResult);
?>
<div class="row">
    <div class="col-sm-12 col-xs-12">
        <h3>Настройки аккаунта &laquo;<?= ($arResult['account']['user_NAME'] . $arResult['account']['user_LAST_NAME'] ? : $arResult['account']['user_LOGIN']) ?>&raquo;</h3>
    </div>
    <div class="col-sm-12 col-xs-12 authinfo">
        <h4>Данные авторизации</h4>
        <div class="row spacer">
            <div class="col-sm-12 col-xs-12">
                <strong style="margin-right: 20px;">Логин:</strong><?= $arResult['account']['user_LOGIN'] ?>
            </div>
        </div>
        <div class="row spacer">
            <div class="col-sm-12 col-xs-12">
                <?= \Hogart\Lk\Helper\Template\Dialog::Button('change-password', 'Изменить пароль', 'btn btn-primary')?>
            </div>
        </div>
    </div>
    <? if (count($arResult['account']['contacts']) || $arResult['account']['is_general']): ?>
    <div class="col-sm-12 col-xs-12 contacts">
        <h4>Контактная информация</h4>
        <div class="row header hidden-xs spacer">
            <div class="col-sm-3"><strong>Имя</strong></div>
            <div class="col-sm-3"><strong>Email</strong></div>
            <div class="col-sm-3"><strong>Телефоны</strong></div>
            <div class="col-sm-3 operations"></div>
        </div>
        <? foreach ($arResult['account']['contacts'] as $contact): ?>
            <div class="row spacer contact" data-contact-id="<?= $contact['guid_id'] ?>">
                <div class="col-sm-3"><strong class="pull-left visible-xs">Имя:</strong><?= ($contact['last_name'] . " " . $contact['name'] . " " . $contact['middle_name']) ?></div>
                <div class="col-sm-3"><strong class="pull-left visible-xs">Email:</strong><?= $contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL][0]['value'] ?></div>
                <div class="col-sm-3"><strong class="pull-left visible-xs">Телефоны:</strong>
                    <?= $contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE][0]['value'] ?>
                    <?= $contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE][1]['value'] ?>
                </div>
                <div class="col-sm-3 operations"></div>
            </div>
        <? endforeach;?>
        <? if ($arResult['account']['is_general']): ?>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-contact-dialog', 'Добавить контактное лицо', 'btn btn-primary')?>
                </div>
            </div>
        <? endif; ?>
    </div>
    <? endif; ?>
    <? if (count($arResult['account']['stores']) || $arResult['account']['is_general']): ?>
    <div class="col-sm-12 col-xs-12 stores">
        <h4>Склады для доставки и самовывоза</h4>
        <? foreach ($arResult['account']['stores'] as $store): ?>
            <div class="row spacer store">
                <div class="col-sm-12 col-xs-12" data-store-id="<?= $store['store_XML_ID'] ?>">
                    <i class="fa fa-star<?= ($store['store_ID'] == $arResult['account']['main_store_id'] ? ' color-green' : '-o') ?>"></i>
                    <?= $store['store_ADDRESS'] ?>
                </div>
            </div>
        <? endforeach;?>
        <? if ($arResult['account']['is_general']): ?>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-store-dialog', 'Добавить склад', 'btn btn-primary')?>
                </div>
            </div>
        <? endif; ?>
    </div>
    <? endif; ?>
    <div class="col-sm-12 col-xs-12 staff">
        <h4>Ваши менеджеры</h4>
    </div>
    <div class="col-sm-12 col-xs-12 another-accounts">
        <h4>Прочие аккаунты холдинга</h4>
    </div>
</div>

<? \Hogart\Lk\Helper\Template\Dialog::Start('change-password', [
    'dialog-options' => 'hashTracking: false, closeOnOutsideClick: false, closeOnEscape: false, closeOnConfirm: false',
    'title' => 'Подтверждение смены пароля'
])?>
    <p>
        После подтверждения Вы получите инструкции на Вашу электроную почту <strong><?= $arResult['account']['user_LOGIN'] ?></strong>
    </p>

<?
$id = \Hogart\Lk\Helper\Template\Dialog::$id;
$handler =<<<JS
    (function() {
      var inst = $('[data-remodal-id="$id"]').remodal();
      $.post("", { action: 'change-password' }, function () {
        inst.close();
      }, 'json');
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('confirmation', $handler);
\Hogart\Lk\Helper\Template\Dialog::End()
?>

<? \Hogart\Lk\Helper\Template\Dialog::Start("add-store-dialog", [
    'title' => 'Добавить склад' 
]) ?>
<? //@todo сделать форму добавления склада ?>
<? \Hogart\Lk\Helper\Template\Dialog::End() ?>

<? \Hogart\Lk\Helper\Template\Dialog::Start("add-contact-dialog", [
    'dialog-options' => 'closeOnConfirm: false',
    'title' => 'Добавить контактное лицо' 
]) ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="add-contact" method="post">
    <div class="form-group">
        <label class="control-label">Фамилия</label>
        <input name="last_name" required="required" type="text" class="form-control" placeholder="Фамилия" data-error="Поле не должно быть пустым">
        <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
        <label class="control-label">Имя</label>
        <input name="name" required="required" type="text" class="form-control" placeholder="Имя" data-error="Поле не должно быть пустым">
        <div class="help-block with-errors"></div>
    </div>
    <div class="form-group">
        <label class="control-label">Отчество</label>
        <input name="middle_name" type="text" class="form-control" placeholder="Отчество">
    </div>
    <div class="form-group">
        <label class="control-label">Email</label>
        <input name="email" type="email" class="form-control" placeholder="Email">
    </div>
    <div class="form-group">
        <label class="control-label">Телефон (моб.)</label>
        <input name="phone[<?= \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_MOBILE ?>]" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
    </div>
    <div class="form-group">
        <label class="control-label">Телефон (гор.)</label>
        <input name="phone[<?= \Hogart\Lk\Entity\ContactInfoTable::PHONE_KIND_STATIC ?>]" data-mask="+7 (999) 999-99-99" type="text" class="form-control" placeholder="Телефон">
    </div>
    <input type="hidden" name="action" value="add-contact">
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
