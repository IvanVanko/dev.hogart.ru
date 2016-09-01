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
//var_dump($arResult['stores']);
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
        <div id="contacts-ajax">
            <div class="row header hidden-xs spacer">
                <div class="col-lg-3 col-sm-3"><strong>Имя</strong></div>
                <div class="col-lg-3 col-sm-3"><strong>Email</strong></div>
                <div class="col-lg-2 col-sm-3"><strong>Телефоны</strong></div>
                <div class="col-lg-2 col-sm-3 operations"></div>
            </div>
            <? $ajax_id = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['edit_contact', 'remove_contact']); ?>
            <? foreach ($arResult['account']['contacts'] as $contact): ?>
                <div class="row vertical-align spacer contact" data-contact-id="<?= $contact['guid_id'] ?>">
                    <? $contact_name = ($contact['last_name'] . " " . $contact['name'] . " " . $contact['middle_name']); ?>
                    <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Имя:</strong> <?= $contact_name ?></div>
                    <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Email:</strong> <a
                            href="mailto:<?= ($email = $contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL][0]['value']) ?>"><?= $email ?></a></div>
                    <div class="col-lg-2 col-sm-3"><strong class="pull-left visible-xs">Телефоны:</strong>
                        <div>
                            <? foreach($contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE] as $phone): ?>
                                <div class="phone-number <?= ("type-" . $phone['phone_kind']) ?>">
                                    <?= $phone['value']; ?>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-3 operations">
                        <strong class="pull-left visible-xs">Операции:</strong>
                        <div class="btn-toolbar" role="toolbar">
                            <div
                                <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent(
                                    'contacts-ajax',
                                    $ajax_id,
                                    ['edit_contact' => $contact['id']],
                                    \Hogart\Lk\Helper\Template\Ajax::DIALOG_EDIT,
                                    [
                                        'title' => 'Редактирование контакта',
                                        'edit_action' => 'edit-contact',
                                        'edit_object' => $contact,
                                        'edit_form_file' => __DIR__ . "/forms/contact.php"
                                    ]
                                ) ?>
                                class="btn btn-default btn-xs">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </div>
                            <div 
                                <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('contacts-ajax', $ajax_id, ['remove_contact' => $contact['id']], \Hogart\Lk\Helper\Template\Ajax::DIALOG_CONFIRMATION, [
                                    'title' => 'Подтверждение удаления контактной информации',
                                    'confirmation' => 'Вы действительно хотите удалить контакт "' . $contact_name . '"?'
                                ]) ?> 
                                class="btn btn-danger btn-xs">
                                
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach;?>
            <? \Hogart\Lk\Helper\Template\Ajax::End($component, $ajax_id); ?>
        </div>
        
        <? if ($arResult['account']['is_general']): ?>
            <div class="row spacer"></div> <!-- feature -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-contact-dialog', 'Добавить контакт', 'btn btn-primary')?>
                </div>
            </div>
        <? endif; ?>
    </div>
    <? endif; ?>
    <? if (count($arResult['account']['stores']) || $arResult['account']['is_general']): ?>
    <div class="col-sm-12 col-xs-12 stores">
        <h4>Склады для доставки и самовывоза</h4>
        <div id="stores-ajax">
            <? $ajax_id = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['fav_store', 'remove_store']); ?>
            <? foreach ($arResult['account']['stores'] as $store): ?>
                <div class="row store spacer">
                    <div class="col-lg-8 col-md-9 col-sm-10 col-xs-10" data-store-id="<?= $store['store_XML_ID'] ?>">
                        <i
                            <?= ($store['store_ID'] != $arResult['account']['main_store_id'] ? \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('stores-ajax', $ajax_id, ['fav_store' => $store['store_ID']]) : '') ?>
                            class="fa fa-star<?= ($store['store_ID'] == $arResult['account']['main_store_id'] ? ' color-green' : '-o') ?>"></i>
                        <?= $store['store_ADDRESS'] ?>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 operations">
                        <div class="btn-toolbar" role="toolbar">
                            <div
                                <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('stores-ajax', $ajax_id, ['remove_store' => $store['store_XML_ID']], \Hogart\Lk\Helper\Template\Ajax::DIALOG_CONFIRMATION, [
                                    'title' => 'Подтверждение удаления склада',
                                    'confirmation' => 'Вы действительно хотите удалить склад <br/> "' . ($store['store_TITLE'] . (!empty(trim($store['store_ADDRESS'])) ? (" (" . $store['store_ADDRESS'] . ")") : "")) . '"?'
                                ]) ?>
                                class="btn btn-danger btn-xs">

                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach;?>
            <? \Hogart\Lk\Helper\Template\Ajax::End($component, $ajax_id); ?>
        </div>
        <? if ($arResult['account']['is_general']): ?>
            <div class="row spacer"></div> <!-- feature -->
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-store-dialog', 'Добавить склад', 'btn btn-primary')?>
                </div>
            </div>
        <? endif; ?>
    </div>
    <? endif; ?>
    <? if (count($arResult['account']['managers'])): ?>
    <div class="col-sm-12 col-xs-12 managers">
        <h4>Ваши менеджеры</h4>
        <div class="row header hidden-xs spacer">
            <div class="col-sm-3 col-md-2 col-lg-1 hidden-xs"></div>
            <div class="col-lg-2 col-sm-3"><strong>Имя</strong></div>
            <div class="col-lg-2 col-sm-3"><strong>Email</strong></div>
            <div class="col-lg-2 col-sm-3"><strong>Телефоны</strong></div>
        </div>
        <? foreach ($arResult['account']['managers'] as $manager): ?>
            <? $name = ($manager['last_name'] . " " . $manager['name'] . " " . $manager['middle_name']); ?>
            <div class="row vertical-align spacer manager" data-manager-id="<?= $manager['guid_id'] ?>">
                <div class="col-sm-3 col-md-2 col-lg-1 avatar hidden-xs">
                    <img src="https://secure.gravatar.com/avatar/06ca41201d38c7d60478358f456001ee?s=64" alt="<?= $name ?>">
                </div>
                <div class="col-lg-2 col-sm-3"><strong class="pull-left visible-xs">Имя:</strong> <?= $name ?></div>
                <div class="col-lg-2 col-sm-3"><strong class="pull-left visible-xs">Email:</strong> <a
                        href="mailto:<?= ($email = $manager['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL][0]['value']) ?>"><?= $email ?></a></div>
                <div class="col-lg-2 col-sm-3"><strong class="pull-left visible-xs">Телефоны:</strong>
                    <div>
                    <? foreach($manager['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE] as $phone): ?>
                        <div class="phone-number <?= ("type-" . $phone['phone_kind']) ?>">
                            <?= $phone['value']; ?>
                        </div>
                    <? endforeach; ?>
                    </div>
                </div>
            </div>
        <? endforeach;?>
    </div>
    <? endif; ?>

    <? if (count($arResult['account']['sub_accounts'])): ?>
    <div class="col-sm-12 col-xs-12 sub-accounts">
        <h4>Прочие аккаунты холдинга</h4>
        <? foreach ($arResult['account']['sub_accounts'] as $sub_account): ?>
            <div class="col-sm-4 col-md-3 col-lg-2">
                <div>
                    <a href="mailto:<?= ($email = $sub_account['user_LOGIN']) ?>"><?= $email ?></a>
                </div>
                <div>
                    <?= ($sub_account['user_NAME'] . $sub_account['user_LAST_NAME']) ?>
                </div>
            </div>
        <? endforeach; ?>
    </div>
    <? endif; ?>
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
    'dialog-options' => 'closeOnConfirm: false',
    'title' => 'Добавить склад',
]) ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="add-store" method="post">
    <div class="form-group" style="position: relative">
        <label>Выберете склады</label>
        <select name="stores[]" class="form-control selectpicker" multiple>
            <? foreach ($arResult['av_stores'] as $store): ?>
                <option value="<?= $store['XML_ID'] ?>"><?= $store['TITLE'] . (!empty(trim($store['ADDRESS'])) ? (" (" . $store['ADDRESS'] . ")") : "") ?></option>
            <? endforeach; ?>
        </select>
    </div>
    <input type="hidden" name="action" value="add-store">
</form>

<?
$id = \Hogart\Lk\Helper\Template\Dialog::$id;
$handler =<<<JS
    (function() {
      $('[data-remodal-id="$id"] form').submit();
    })
JS;
\Hogart\Lk\Helper\Template\Dialog::Event('confirmation', $handler);
\Hogart\Lk\Helper\Template\Dialog::End()
?>

<? \Hogart\Lk\Helper\Template\Dialog::Start("add-contact-dialog", [
    'dialog-options' => 'closeOnConfirm: false',
    'title' => 'Добавить контакт'
]) ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="add-contact" method="post">
    <? include __DIR__ . "/forms/contact.php" ?>
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
