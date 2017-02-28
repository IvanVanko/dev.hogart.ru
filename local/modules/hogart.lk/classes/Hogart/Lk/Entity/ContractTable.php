<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:11
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Exchange\RabbitMQ\Consumer;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\ContractExchange;
use Hogart\Lk\Exchange\SOAP\Request\Contract;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица Договоры
 * @package Hogart\Lk\Entity
 */
class ContractTable extends AbstractEntity
{
    /** НДС - Без НДС */
    const VAT_0 = 0;
    /** НДС - 100% */
    const VAT_10 = 10;
    /** НДС - 18% */
    const VAT_18 = 18;

    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_contract";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField("id", [
                'primary' => true,
                "autocomplete" => true
            ]),
            new GuidField("guid_id"),
            new IntegerField("company_id"),
            new ReferenceField("company", __NAMESPACE__ . "\\CompanyTable", ["=this.company_id" => "ref.id"]),
            new IntegerField("hogart_company_id"),
            new ReferenceField("hogart_company", __NAMESPACE__ . "\\HogartCompanyTable", ["=this.hogart_company_id" => "ref.id"]),
            new StringField("number"),
            new DateField("start_date"),
            new DateField("end_date"),
            new BooleanField("prolongation", [
                'default_value' => false
            ]),
            new StringField("currency_code"),
            new ReferenceField("currency", "Bitrix\\Currency\\CurrencyTable", ["=this.currency_code" => "ref.CURRENCY"]),
            new BooleanField("perm_item"),
            new BooleanField("perm_promo"),
            new BooleanField("perm_clearing"),
            new BooleanField("perm_card"),
            new BooleanField("perm_cash"),
            new BooleanField("cash_control"),
            new BooleanField("is_credit"),
            new FloatField("sale_max_money"),
            new StringField("cash_limit"),
            new StringField("deferral"),
            new StringField("credit_limit"),
            new BooleanField("have_original"),
            new BooleanField("accept"),
            new EnumField("vat_rate", [
                'values' => [
                    self::VAT_0,
                    self::VAT_10,
                    self::VAT_18
                ],
                'default_value' => self::VAT_18
            ]),
            new BooleanField("vat_include", [
                'default_value' => true
            ]),
            new BooleanField("is_active")
        ];
    }

    /**
     * @param array $contract
     * @param bool $with_company_name
     * @param string $prefix
     * @return string
     */
    public static function showName($contract = [], $with_company_name = false, $prefix = '')
    {
        if ($contract[$prefix . 'accept'])
            $name = "Договор " . ($contract[$prefix . "number"] ? "№" . $contract[$prefix . "number"] : "") . " от " . $contract[$prefix . "start_date"];
        else
            $name = "Договор №<sup>получение</sup> от " . $contract[$prefix . "start_date"];

        $name .= " ({$contract[$prefix . 'currency_code']})";
        if ($with_company_name) {
            $company = CompanyTable::getById($contract[$prefix . 'company_id'])->fetch();
            $company_name = $company[$prefix . 'name'];
            $name .=<<<HTML
<span class="footer-text">$company_name</span>
HTML;
        }

        return $name;
    }

    /**
     * @param array $contract
     * @param string $prefix
     * @return string
     */
    public static function showStatus($contract = [], $prefix = '')
    {
        $status = "Обрабатывается";

        if ($contract[$prefix . 'accept'])
            $status = "Подтвержден";

        if ($contract[$prefix . 'have_original'])
            $status = "Получены оригиналы";

        return $status;
    }

    /**
     * @param $account_id
     * @param $contract_id
     * @return bool
     */
    public static function isAccountContract($account_id, $contract_id)
    {
        $relation = ContractTable::getRow([
            'filter' => [
                '=id' => $contract_id,
                '=company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.id' => $account_id
            ]
        ]);
        return !empty($relation);
    }

    /**
     * @param $account_id
     * @param bool $is_active
     * @return array
     */
    public static function getByAccountId($account_id, $is_active = true)
    {
        return self::getList([
            'filter' => [
                '=company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.id' => $account_id,
                '=company.is_active' => $is_active,
                '=is_active' => $is_active
            ],
            'select' => [
                '*',
                'c_' => 'company'
            ]
        ])->fetchAll();
    }

    /**
     * @param $id
     * @return array
     */
    public static function getByCompanyId($id)
    {
        return self::getList([
            'filter' => [
                '=company_id' => $id,
                '=is_active' => true
            ]
        ])->fetchAll();
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_guid_id', ['guid_id' => 36]),
            new Index('idx_contract_entity_most', ['company_id', 'hogart_company_id']),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    public static function getContractForExchange($id)
    {
        return self::getList([
            'filter' => [
                '=id' => $id
            ],
            'select' => [
                '*',
                'account_id' => 'company.Hogart\Lk\Entity\AccountCompanyRelationTable:company.account.id',
                'co_' => 'company',
                'hco_' => 'hogart_company',
            ]
        ])->fetchAll();
    }

    public static function onAfterAdd(Event $event)
    {
        $id = $event->getParameter('id');
        $fields = $event->getParameter('fields');
        if (!empty($id) && empty($fields['guid_id'])) {
            self::publishToRabbit(new ContractExchange(), new Contract(self::getContractForExchange($id)));
        }
    }

    public static function onAfterUpdate(Event $event)
    {
        $fields = $event->getParameter('fields');
        $id = $event->getParameter('id');

        if (!$fields['is_active']) {
            $accounts = AccountTable::getList([
                'filter' => [
                    '=main_contract_id' => $id
                ]
            ])->fetchAll();

            foreach ($accounts as $account) {
                AccountTable::update($account['id'], [
                    'main_contract_id' => 0
                ]);
            }
        }
    }
}
