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
\Hogart\Lk\Helper\Template\Suggestions::init();
?>
<div class="row">
    <div class="col-sm-12 col-xs-12">
        <h3>Настройки аккаунта &laquo;<?= $arResult['account']['user_LOGIN'] ?>&raquo;</h3>
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
                <div class="col-lg-4 col-sm-3"><strong>Имя</strong></div>
                <div class="col-lg-3 col-sm-3"><strong>Email</strong></div>
                <div class="col-lg-2 col-sm-3"><strong>Телефоны</strong></div>
                <div class="col-lg-2 col-sm-3 operations"></div>
            </div>
            <? $contacts_node = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['edit_contact', 'remove_contact']); ?>
            <? foreach ($arResult['account']['contacts'] as $contact): ?>
                <div class="row vertical-align spacer contact" data-contact-id="<?= $contact['guid_id'] ?>">
                    <? $contact_name = ($contact['last_name'] . " " . $contact['name'] . " " . $contact['middle_name']); ?>
                    <div class="col-lg-4 col-sm-3"><strong class="pull-left visible-xs">Имя:</strong> <?= $contact_name ?></div>
                    <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Email:</strong> <a
                            href="mailto:<?= ($email = $contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL][0]['value']) ?>"><?= $email ?></a></div>
                    <div class="col-lg-2 col-sm-3"><strong class="pull-left visible-xs">Телефоны:</strong>
                        <div>
                            <? foreach($contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE] as $phone): ?>
                                <div class="phone-number <?= ("type-" . $phone['phone_kind']) ?>">
                                    <?= \Hogart\Lk\Entity\ContactInfoTable::formatPhone($phone['value']) ?>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-3 operations">
                        <div class="btn-toolbar" role="toolbar">
                            <div
                                <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent(
                                    'contacts-ajax',
                                    $contacts_node->getId(),
                                    ['edit_contact' => $contact['id']],
                                    \Hogart\Lk\Helper\Template\Ajax::DIALOG_EDIT,
                                    [
                                        'title' => 'Редактирование контакта',
                                        'edit_action' => 'edit-contact',
                                        'edit_object' => $contact,
                                        'template_file' => __DIR__ . "/forms/contact.php"
                                    ]
                                ) ?>
                                class="btn btn-default btn-xs">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </div>
                            <div 
                                <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('contacts-ajax', $contacts_node->getId(), ['remove_contact' => $contact['id']], \Hogart\Lk\Helper\Template\Ajax::DIALOG_CONFIRMATION, [
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
            <? \Hogart\Lk\Helper\Template\Ajax::End($contacts_node->getId()); ?>
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
        <? foreach ($arResult['account']['stores'] as $store): ?>
            <div class="row store spacer">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" data-store-id="<?= $store['XML_ID'] ?>">
                    <i
                        <?= ($store['store_ID'] != $arResult['account']['main_store_id'] ? \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('stores-ajax', $ajax_id, ['fav_store' => $store['ID']]) : '') ?>
                        class="fa fa-star<?= ($store['ID'] == $arResult['account']['main_store_id'] ? ' color-green' : '-o') ?>"></i>
                    <?= $store['ADDRESS'] ?>
                </div>
            </div>
        <? endforeach;?>
        </div>
    </div>
    <? endif; ?>
    <? if (count($arResult['account']['managers'])): ?>
    <div class="col-sm-12 col-xs-12 managers">
        <h4>Ваши менеджеры</h4>
        <div class="row header hidden-xs spacer">
            <div class="col-lg-4 col-sm-3"><strong>Имя</strong></div>
            <div class="col-lg-3 col-sm-3"><strong>Email</strong></div>
            <div class="col-lg-2 col-sm-3"><strong>Телефоны</strong></div>
            <div class="col-lg-2 col-sm-3"></div>
        </div>
        <? foreach ($arResult['account']['managers'] as $manager): ?>
            <? $name = ($manager['last_name'] . " " . $manager['name'] . " " . $manager['middle_name']); ?>
            <div class="row vertical-align spacer manager" data-manager-id="<?= $manager['guid_id'] ?>">
                <div class="col-lg-4 col-sm-3">
                    <div class="avatar hidden-xs">
                        <? if (!empty($manager['photo'])): ?>
                            <img src="<?= $manager['photo'] ?>" alt="<?= $name ?>">
                        <? endif; ?>
                    </div>
                    <strong class="pull-left visible-xs">Имя:</strong>
                    <span>
                        <?= $name ?>
                        <? if ($manager['id'] == $arResult['account']['main_manager_id']): ?>
                            <sup><span class="label label-primary label-xs">основной</span></sup>
                        <? endif; ?>
                    </span>
                </div>
                <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Email:</strong>
                    <? ($email = $manager['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL][0]['value']) ?>
                    <? if (!empty($email)): ?>
                    <a
                        href="mailto:<?= ($email = $manager['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL][0]['value']) ?>"><?= $email ?></a>
                    <? endif; ?>
                </div>

                <div class="col-lg-2 col-sm-3"><strong class="pull-left visible-xs">Телефоны:</strong>
                    <div>
                    <? foreach($manager['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE] as $phone): ?>
                        <div class="phone-number <?= ("type-" . $phone['phone_kind']) ?>">
                            <?= \Hogart\Lk\Entity\ContactInfoTable::formatPhone($phone['value']) ?>
                        </div>
                    <? endforeach; ?>
                    </div>
                </div>
                <div class="col-lg-2 col-sm-3">
                    <a href="#">
                        <i class="fa fa-question fa-lg text-warning" aria-hidden="true"></i>
                        Задать вопрос менеджеру
                    </a>
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
        <label>Выберите склады</label>
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
