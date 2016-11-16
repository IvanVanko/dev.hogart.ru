<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var \CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var \CBitrixComponent $component */
/** @ver array $companies */
$this->setFrameMode(true);

use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Helper\Template\Dialog;
use Hogart\Lk\Entity\AddressTypeTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\AddressTable;

\Hogart\Lk\Helper\Template\Suggestions::init();
?>
<div id="companies-ajax">
    <? $companies_node = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['remove_company']); ?>
    <div class="row">
        <div class="col-sm-12 col-xs-12">
            <h3>Юридические лица и договора</h3>
        </div>
        <div class="col-sm-12 col-xs-12 companies">
            <form method="post">
                <input type="hidden" name="action" value="change_company">
                <div class="row spacer form-group">
                    <div data-loader-wrapper="#companies-ajax" id="company-ajax" class="col-sm-8 col-xs-12">
                        <? $company_select_node = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['fav_company'], $companies_node); ?>
                        <div class="row">
                            <? if ($arResult['current_company']['id']): ?>
                            <div class="col-sm-1">

                                <div
                                    <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('company-ajax', $company_select_node->getId(), ['fav_company' => $arResult['current_company']['id']]) ?>
                                    class="btn btn-<?= ($arResult['current_company']['is_favorite'] ? 'primary' : 'default' ) ?>">
                                    <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                                </div>
                            </div>
                            <? endif; ?>
                            <div class="col-sm-11">
                                <div class="form-group">
                                    <select onchange="this.form.submit();" class="form-control selectpicker" name="cc_id" id="current_company">
                                        <? foreach($arResult['companies'] as $company): ?>
                                            <option value="<?= $company['id']?>" <?= $company['selected']?>><?= $company['name']; ?></option>
                                        <? endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <? \Hogart\Lk\Helper\Template\Ajax::End($company_select_node->getId()); ?>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="btn-toolbar" role="toolbar">
                            <?/*= Dialog::Link('edit-company-dialog', '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>', 'btn btn-default')*/?>
                            <? if ($arResult['current_company']['id']): ?>

                                <div
                                    <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent(
                                        'companies-ajax',
                                        $companies_node->getId(),
                                        ['edit_company' => $arResult['current_company']['id']],
                                        \Hogart\Lk\Helper\Template\Ajax::DIALOG_EDIT,
                                        [
                                            'title' => 'Редактирование компании',
                                            'edit_action' => 'edit-company',
                                            'edit_object' => $arResult['current_company'],
                                            'template_file' => __DIR__ . "/forms/company.php",
                                            'template_vars' => ["edit_company" => $arResult['current_company']],
                                            'dialog_event_opening' => 'openingCompanyEdit'
                                        ]
                                    ) ?>
                                    class="btn btn-info">
                                    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                </div>
                                <? if ($arResult['account']['is_general']): ?>
                                    <div
                                        <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('companies-ajax', $companies_node->getId(), ['remove_company' => $arResult['current_company']['id']], \Hogart\Lk\Helper\Template\Ajax::DIALOG_CONFIRMATION, [
                                            'title' => 'Подтверждение удаления компании',
                                            'confirmation' => 'Вы действительно хотите удалить компанию "' . $arResult['current_company']['name'] . '"?<br><br> После удаления на данную компанию нельзя будет сформировать новый счет.<br> История по работе и существующие счета сохранятся.'
                                        ]) ?>
                                        class="btn btn-danger">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </div>
                                <? endif; ?>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row spacer">
                <div class="col-sm-12 col-xs-12">
                    <?= Dialog::Button('add-company-dialog', 'Добавить компанию', 'btn btn-primary')?>
                </div>
            </div>
        </div>
        <? if ($arResult['current_company']['id'] && (count($arResult['current_company']['contracts']))): ?>
            <div class="col-sm-12 col-xs-12 contracts">
                <div id="contracts-ajax">
                    <? $contracts_node = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['remove_contract'], $companies_node); ?>
                    <div class="row spacer-20">
                        <div class="col-sm-12">
                            <div class="delimiter color-green"></div>
                        </div>
                    </div>
                    <h4>Договоры компании</h4>
                    <div class="row header hidden-xs spacer">
                        <div class="col-lg-3 col-sm-3"><strong>Договор</strong></div>
                        <div class="col-lg-2 col-sm-2"><strong>Пролонгация</strong></div>
                        <div class="col-lg-2 col-sm-2"><strong>Срок действия</strong></div>
                        <div class="col-lg-2 col-sm-2"><strong>Статус</strong></div>
                        <div class="col-lg-3 col-sm-3"></div>
                    </div>
                    <? foreach ($arResult['current_company']['contracts'] as $contract): ?>
                        <div class="row vertical-align spacer contract" data-contract-id="<?= $contract['guid_id'] ?>">
                            <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Договор:</strong>

                                <? if (count($arResult['current_company']['contracts']) > 1): ?>
                                    <i
                                        <?= ($contract['id'] != $arResult['account']['main_contract_id'] ? \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('contracts-ajax', $contracts_node->getId(), ['fav_contract' => $contract['id']]) : '') ?>
                                        class="fa fa-star<?= ($contract['id'] == $arResult['account']['main_contract_id'] ? ' color-green' : '-o') ?>"></i>
                                <? endif; ?>
                                <span>
                                    <?= ContractTable::showName($contract); ?>
                                </span>
                            </div>
                            <div class="col-lg-2 col-sm-2"><strong class="pull-left visible-xs">Пролонгация:</strong>
                                <span class="glyphicon glyphicon-<?= ($contract['prolongation'] ? "ok" : "remove") ?> color-<?= ($contract['prolongation'] ? "primary" : "danger") ?>" aria-hidden="true"></span>
                            </div>
                            <div class="col-lg-2 col-sm-2"><strong class="pull-left visible-xs">Срок действия:</strong>
                                <span>
                                <?= $contract['end_date']->format('d/m/Y') ?>
                                    <? if ($contract['end_date']->getTimestamp() < (new \Bitrix\Main\Type\DateTime())->getTimestamp()): ?>
                                        <sup><span class="label label-danger label-xs">истек</span></sup>
                                    <? endif; ?>
                                </span>
                            </div>
                            <div class="col-lg-2 col-sm-2"><strong class="pull-left visible-xs">Статус:</strong> <?= ContractTable::showStatus($contract); ?></div>
                            <div class="col-lg-3 col-sm-3">
                                <? //выключено, но код оставил, чтобы, если что, не писать с нуля  ?>
                                <? if (false && !$contract['have_original']): ?>
                                <?= \Hogart\Lk\Helper\Template\Ajax::Link(
                                    '<i class="fa fa-file-o"></i> Запросить оригиналы',
                                    'contracts-ajax',
                                    $contracts_node->getId(),
                                    ['get_docs' => $contract['id']],
                                    '',
                                    \Hogart\Lk\Helper\Template\Ajax::DIALOG_CONFIRMATION,
                                    [
                                        'title' => 'Запрос оригиналов документов',
                                        'confirmation' => 'Вы действительно хотите сделать запрос на получения оригиналов документов до договору "' . ContractTable::showName($contract) . '"?'
                                    ]
                                ) ?>
                                <? endif; ?>

                                <? $manager_feedback_params = $APPLICATION->IncludeComponent("hogart.lk:account.manager.feedback", "", [
                                    "SUBJECT" => "Запрос от {$account_name} ({$arResult['account']['user_LOGIN']})" . " по " . ContractTable::showName($contract),
                                ]); ?>
                                <? if (!empty($manager_feedback_params['manager']['email'])): ?>
                                    <a data-remodal-target="<?= $manager_feedback_params['dialog'] ?>" href="javascript:void(0)">
                                        <i class="fa fa-question fa-lg text-warning" aria-hidden="true"></i>
                                        Задать вопрос менеджеру
                                    </a>
                                <? endif; ?>

                            </div>
                        </div>
                    <? endforeach;?>
                    <? \Hogart\Lk\Helper\Template\Ajax::End($contracts_node->getId()); ?>
                </div>
            </div>
        <? endif; ?>
        <? if ($arResult['current_company']['id'] && (count($arResult['current_company']['contacts']) || $arResult['account']['is_general'])): ?>
            <div class="col-sm-12 col-xs-12 contacts">
                <div id="contacts-ajax">
                    <? $contacts_node = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['edit_contact', 'remove_contact'], $companies_node); ?>
                    <div class="row spacer-20">
                        <div class="col-sm-12">
                            <div class="delimiter color-green"></div>
                        </div>
                    </div>
                    <h4>Контакты компании</h4>
                    <div class="row header hidden-xs spacer">
                        <div class="col-lg-4 col-sm-3"><strong>Контактное лицо</strong></div>
                        <div class="col-lg-3 col-sm-3"><strong>Email</strong></div>
                        <div class="col-lg-3 col-sm-3"><strong>Телефоны</strong></div>
                        <div class="col-lg-2 col-sm-3 operations"></div>
                    </div>
                    <? foreach ($arResult['current_company']['contacts'] as $contact): ?>
                        <div class="row vertical-align spacer contact" data-contact-id="<?= $contact['guid_id'] ?>">
                            <? $contact_name = ($contact['last_name'] . " " . $contact['name'] . " " . $contact['middle_name']); ?>
                            <? ($email = $contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_EMAIL][0]['value']) ?>
                            <div class="col-lg-4 col-sm-3"><strong class="pull-left visible-xs">Контактное лицо:</strong> <?= $contact_name ?></div>
                            <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Email:</strong>
                                <? if (!empty($email)): ?>
                                    <a href="mailto:<?= $email ?>"><?= $email ?></a>
                                <? endif; ?>
                            </div>
                            <div class="col-lg-3 col-sm-3"><strong class="pull-left visible-xs">Телефоны:</strong>
                                <div>
                                    <? foreach($contact['info'][\Hogart\Lk\Entity\ContactInfoTable::TYPE_PHONE] as $phone): ?>
                                        <div class="phone-number <?= ("type-" . $phone['phone_kind']) ?>">
                                            <?= \Hogart\Lk\Entity\ContactInfoTable::formatPhone($phone['value']) ?>
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
        <? $delivery_addresses = $arResult['current_company']['addresses'][AddressTypeTable::getByField('code', AddressTypeTable::TYPE_DELIVERY)['id']]; ?>
        <? if ($arResult['current_company']['id'] && (count($delivery_addresses) || $arResult['account']['is_general'])): ?>
            <div class="col-sm-12 col-xs-12 addresses">
                <div id="address-ajax">
                    <? $address_node = \Hogart\Lk\Helper\Template\Ajax::Start($component, ['edit_address', 'remove_address'], $companies_node); ?>
                    <div class="row spacer-20">
                        <div class="col-sm-12">
                            <div class="delimiter color-green"></div>
                        </div>
                    </div>
                    <h4>Адреса доставки компании</h4>
                    <div class="row header hidden-xs spacer">
                        <div class="col-lg-10 col-sm-9"><strong>Адрес</strong></div>
                        <div class="col-lg-2 col-sm-3 operations"></div>
                    </div>
                    <? foreach ($delivery_addresses as $address): ?>
                        <div class="row vertical-align spacer contact" data-contract-id="<?= $address['fias_code'] ?>">
                            <div class="col-lg-10 col-sm-9"><strong class="pull-left visible-xs">Адрес:</strong> <?= AddressTable::getValue($address) ?></div>
                            <div class="col-lg-2 col-sm-3 operations">
                                <strong class="pull-left visible-xs">Операции:</strong>
                                <div class="btn-toolbar" role="toolbar">
                                    <div
                                        <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent(
                                            'contacts-ajax',
                                            $address_node->getId(),
                                            ['edit_contact' => $address['id']],
                                            \Hogart\Lk\Helper\Template\Ajax::DIALOG_EDIT,
                                            [
                                                'title' => 'Редактирование адреса',
                                                'edit_action' => 'edit-address',
                                                'edit_object' => $address,
                                                'template_file' => __DIR__ . "/forms/address.php",
                                                'dialog_event_opening' => 'openingAddressEdit'
                                            ]
                                        ) ?>
                                        class="btn btn-default btn-xs">
                                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                    </div>
                                    <div
                                        <?= \Hogart\Lk\Helper\Template\Ajax::OnClickEvent('address-ajax', $address_node->getId(), ['remove_address' => $address['fias_code']], \Hogart\Lk\Helper\Template\Ajax::DIALOG_CONFIRMATION, [
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
                    <? \Hogart\Lk\Helper\Template\Ajax::End($address_node->getId()); ?>
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
    <? \Hogart\Lk\Helper\Template\Ajax::End($companies_node->getId()); ?>
</div>
<? Dialog::Start("add-company-dialog", [
    'dialog-options' => 'closeOnConfirm: false',
    'title' => 'Добавление юридического/физического лица'
]) ?>
<form action="<?= $APPLICATION->GetCurPage() ?>" name="add-company" method="post">
    <? include __DIR__ . "/forms/company.php" ?>
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
<!-- Диалог добавления контактных лиц -->
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
<!-- Конец диалога добавления контактных лиц -->

<!-- Диалог добавления адреса -->
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
<!-- Конец диалога добавления адреса -->

<!-- Диалог просмотра данных по юр. лице -->
<? \Hogart\Lk\Helper\Template\Dialog::Start("view-company-dialog", [
    'dialog-options' => '',
    'title' => 'Информация о компании'
]) ?>
<?
\Hogart\Lk\Helper\Template\Dialog::End([
    'cancel' => false
])
?>
<!-- Конец диалога просмотра данных по юр. лице -->