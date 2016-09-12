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
/** @ver array $companies */
$this->setFrameMode(true);

use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Helper\Template\Dialog;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\ContractTable;

\Hogart\Lk\Helper\Template\Suggestions::init();
?>

<div class="row">
    <div class="col-sm-12 col-xs-12">
        <h3>Юридические лица и договора</h3>
    </div>
    <div class="col-sm-12 col-xs-12 companies">
        <form method="post">
            <input type="hidden" name="action" value="change_company">
            <div id="companies-ajax" class="row spacer form-group">
                <? $ajax_id = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['fav_company']); ?>
                <div class="col-sm-10 col-xs-10">
                    <select onchange="this.form.submit();" class="form-control selectpicker" name="cc_id" id="current_company">
                        <? foreach($arResult['companies'] as $company): ?>
                            <option value="<?= $company['id']?>" <?= $company['selected']?>><?= $company['name']; ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2 col-xs-2">
                    <?= Dialog::Link('edit-company-dialog', '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', 'btn btn-default')?>

                    <div
                        <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('companies-ajax', $ajax_id, ['fav_company' => $arResult['current_company']['id']]) ?>
                        class="btn btn-<?= ($arResult['current_company']['is_favorite'] ? 'primary' : 'default' ) ?>">

                        <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                    </div>
                </div>
                <? \Hogart\Lk\Helper\Template\Ajax::End($component, $ajax_id); ?>
            </div>
        </form>
        <div class="row spacer">
            <div class="col-sm-12 col-xs-12">
                <?= Dialog::Button('add-company-dialog', 'Добавить компанию', 'btn btn-primary')?>
            </div>
        </div>
    </div>
    <? if (count($arResult['current_company']['contracts']) || $arResult['account']['is_general']): ?>
        <div class="col-sm-12 col-xs-12 contracts">
            <div id="contracts-ajax">
                <? $ajax_id = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['remove_contract']); ?>
                <div class="row header hidden-xs spacer">
                    <div class="col-lg-3 col-sm-3"><strong>Договор</strong></div>
                    <div class="col-lg-3 col-sm-3"><strong>Статус</strong></div>
                    <div class="col-lg-2 col-sm-3"><strong>Запрос</strong></div>
                    <div class="col-lg-2 col-sm-3 operations"></div>
                </div>
                <? foreach ($arResult['current_company']['contracts'] as $contract): ?>
                    <div class="row vertical-align spacer contact" data-contract-id="<?= $contract['guid_id'] ?>">
                        <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Договор:</strong> <?= ContractTable::showName($contract); ?></div>
                        <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Статус:</strong> <?= ContractTable::showStatus($contract); ?></div>
                        <div class="col-lg-2 col-sm-3"><strong class="pull-left visible-xs">Запрос:</strong></div>
                        <div class="col-lg-2 col-sm-3 operations"></div>
                    </div>
                <? endforeach;?>
                <? \Hogart\Lk\Helper\Template\Ajax::End($component, $ajax_id); ?>
            </div>

            <? if ($arResult['account']['is_general']): ?>
                <div class="row spacer"></div> <!-- feature -->
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-contract-dialog', 'Создать договор', 'btn btn-primary')?>
                    </div>
                </div>
            <? endif; ?>
        </div>
    <? endif; ?>
    <? if (count($arResult['current_company']['contacts']) || $arResult['account']['is_general']): ?>
        <div class="col-sm-12 col-xs-12 contacts">
            <div id="contacts-ajax">
                <? $ajax_id = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['edit_contact', 'remove_contact']); ?>
                <div class="row header hidden-xs spacer">
                    <div class="col-lg-3 col-sm-3"><strong>Контактное лицо</strong></div>
                    <div class="col-lg-3 col-sm-3"><strong>Email</strong></div>
                    <div class="col-lg-2 col-sm-3"><strong>Телефоны</strong></div>
                    <div class="col-lg-2 col-sm-3 operations"></div>
                </div>
                <? foreach ($arResult['current_company']['contacts'] as $contact): ?>
                    <div class="row vertical-align spacer contact" data-contact-id="<?= $contact['guid_id'] ?>">
                        <? $contact_name = ($contact['last_name'] . " " . $contact['name'] . " " . $contact['middle_name']); ?>
                        <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Контактное лицо:</strong> <?= $contact_name ?></div>
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
                                        'title' => 'Подтверждение удаления контактного лица',
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
    <? $delivery_addresses = $arResult['current_company']['addresses'][AddressTypeTable::getByField('code', AddressTypeTable::TYPE_DELIVERY)['id']]; ?>
    <? if (count($delivery_addresses) || $arResult['account']['is_general']): ?>
        <div class="col-sm-12 col-xs-12 addresses">
            <div id="address-ajax">
                <? $ajax_id = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['edit_address', 'remove_address']); ?>
                <div class="row header hidden-xs spacer">
                    <div class="col-lg-8 col-sm-9"><strong>Адрес</strong></div>
                    <div class="col-lg-2 col-sm-3 operations"></div>
                </div>
                <? foreach ($delivery_addresses as $address): ?>
                    <div class="row vertical-align spacer contact" data-contract-id="<?= $address['fias_code'] ?>">
                        <div class="col-lg-8 col-sm-9"><strong class="pull-left visible-xs">Адрес:</strong> <?= $address['value'] ?></div>
                        <div class="col-lg-2 col-sm-3 operations">
                            <strong class="pull-left visible-xs">Операции:</strong>
                            <div class="btn-toolbar" role="toolbar">
                                <div
                                    <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent(
                                        'contacts-ajax',
                                        $ajax_id,
                                        ['edit_contact' => $address['id']],
                                        \Hogart\Lk\Helper\Template\Ajax::DIALOG_EDIT,
                                        [
                                            'title' => 'Редактирование адреса',
                                            'edit_action' => 'edit-address',
                                            'edit_object' => $address,
                                            'edit_form_file' => __DIR__ . "/forms/address.php",
                                            'dialog_event_opening' => 'openingAddressEdit'
                                        ]
                                    ) ?>
                                    class="btn btn-default btn-xs">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </div>
                                <div
                                    <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('address-ajax', $ajax_id, ['remove_address' => $address['fias_code']], \Hogart\Lk\Helper\Template\Ajax::DIALOG_CONFIRMATION, [
                                        'title' => 'Подтверждение удаления адреса доставки',
                                        'confirmation' => 'Вы действительно хотите удалить адрес "' . $address['value'] . '"?'
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
                        <?= \Hogart\Lk\Helper\Template\Dialog::Button('add-address-dialog', 'Добавить адрес доставки', 'btn btn-primary')?>
                    </div>
                </div>
            <? endif; ?>
        </div>
    <? endif; ?>
</div>
<? Dialog::Start("add-company-dialog", [
    'dialog-options' => 'closeOnConfirm: false',
    'title' => 'Добавление юридического/физического лица'
]) ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="add-company" method="post">
    <div class="row vertical-align">
        <div class="col-sm-8">
            <select name="company_type" class="form-control selectpicker">
                <option value="<?= CompanyTable::TYPE_LEGAL_ENTITY ?>">Юридическое лицо</option>
                <option value="<?= CompanyTable::TYPE_INDIVIDUAL_ENTREPRENEUR ?>">Индивидуальный предприниматель</option>
                <option value="<?= CompanyTable::TYPE_INDIVIDUAL ?>">Физическое лицо</option>
            </select>
        </div>
        <div class="col-sm-4 pull-right text-right">
            <label class="checkbox-inline">
                Активно&nbsp;<input data-switch checked="checked" type="checkbox" name="is_active" value="1">
            </label>
        </div>
    </div>
    <? include (__DIR__ . "/forms/company_type_" . CompanyTable::TYPE_LEGAL_ENTITY . ".php"); ?>
    <? include (__DIR__ . "/forms/company_type_" . CompanyTable::TYPE_INDIVIDUAL_ENTREPRENEUR . ".php"); ?>
    <? include (__DIR__ . "/forms/company_type_" . CompanyTable::TYPE_INDIVIDUAL . ".php"); ?>
    <input type="hidden" name="action" value="add-company">
</form>
<?
$id = Dialog::$id;
$handler =<<<JS
    (function() {
      $('[data-remodal-id="$id"] form').validator();
    })
JS;
Dialog::Event('opening', $handler);
$handler =<<<JS
    (function() {
      $('[data-remodal-id="$id"] form').submit();
    })
JS;
Dialog::Event('confirmation', $handler);
Dialog::End()
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

<? \Hogart\Lk\Helper\Template\Dialog::Start("add-address-dialog", [
    'dialog-options' => 'closeOnConfirm: false',
    'title' => 'Добавить адрес доставки'
]) ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="add-address" method="post">
    <? include __DIR__ . "/forms/address.php" ?>
    <input type="hidden" name="action" value="add-address">
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

<? \Hogart\Lk\Helper\Template\Dialog::Start("add-contract-dialog", [
    'dialog-options' => 'closeOnConfirm: false',
    'title' => 'Создать договор'
]) ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="add-contract" method="post">
    <? include __DIR__ . "/forms/contract.php" ?>
    <input type="hidden" name="action" value="add-contract">
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
