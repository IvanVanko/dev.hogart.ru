<?php
/**
 * Компонент отображения Юридических лиц
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 18.08.2016 16:27
 *
 * @var $this CBitrixComponent
 * @var $USER CUser
 * @global CMain $APPLICATION
 */
if (!CModule::IncludeModule("hogart.lk")) {
    $this->abortResultCache();
    ShowError("Не установлен модуль \"Модуль личного кабинета компании Хогарт\"");
    return;
}

use Hogart\Lk\Helper\Template\FlashError;
use Hogart\Lk\Entity\AccountTable;
use Hogart\Lk\Entity\ContactRelationTable;
use Hogart\Lk\Entity\ContractTable;
use Hogart\Lk\Entity\AccountCompanyRelationTable;
use Hogart\Lk\Entity\AddressTable;
use Hogart\Lk\Entity\HogartCompanyTable;
use Hogart\Lk\Entity\PaymentAccountRelationTable;


if (!$this->initComponentTemplate())
    return;

global $USER, $APPLICATION, $CACHE_MANAGER;
$account = AccountTable::getAccountByUserID($USER->GetID());

// Юридические лица (они же компании)
$account = AccountTable::getAccountById($account['id']);

if ($account['id']) {
    include (__DIR__ . "/proceed_request.php");

    $account['is_general'] = AccountTable::isGeneralAccount($account['id']);

    $companies = AccountCompanyRelationTable::getByAccountId($account['id']);
    if (count($companies) == 1) {
        $current_company = &reset($companies);
    } else {
        if (empty($_SESSION['current_company_id'])) {
            foreach ($companies as &$company) {
                if ($company['is_favorite']) {
                    $current_company = &$company;
                    break;
                }
            }
            if (empty($current_company)) {
                $current_company = &reset($companies);
            }
        } else {
            $current_company = &$companies[$_SESSION['current_company_id']];
        }
    }

    $_SESSION['current_company_id'] = $current_company['id'];

    foreach($companies as $key=> &$company){
        if($company['id'] == $current_company['id'])
            $company['selected'] = 'selected';

        $company['contracts'] = ContractTable::getByCompanyId($company['id']);
        $company['contacts'] = ContactRelationTable::getContactsByOwner($company['id'], ContactRelationTable::OWNER_TYPE_CLIENT_COMPANY);
        $company['addresses'] = AddressTable::getByOwner($company['id'], AddressTable::OWNER_TYPE_CLIENT_COMPANY);
        $company['payment_account'] = PaymentAccountRelationTable::getByOwner(
            $company['id'],
            PaymentAccountRelationTable::OWNER_TYPE_CLIENT_COMPANY,
            [
                '=payment_account.is_active' => true
            ],
            [
                '*',
                '_' => 'payment_account'
            ]
        );
    }

    $arResult['account'] = $account;
    $arResult['companies'] = $companies;
    $arResult['current_company'] = $arResult['companies'][$current_company['id']];
    $arResult['hogart_companies'] = HogartCompanyTable::getList([
        'filter' => [
            '=is_active' => true
        ]
    ])->fetchAll();

    $arResult['currency'] = CStorage::getVar('HOGART.CURRENCIES');

    $this->includeComponentTemplate();
} else {
    new FlashError("У Вас нет доступа в данный раздел");
    LocalRedirect("/");
}