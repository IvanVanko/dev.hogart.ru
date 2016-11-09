<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 22:44
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\CompanyExchange;
use Hogart\Lk\Exchange\SOAP\Request\Company;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Field\HashSum;

/**
 * Таблица Компаний клиента
 * @package Hogart\Lk\Entity
 */
class CompanyTable extends AbstractEntity
{
    /** Тип - Юр. лицо */
    const TYPE_LEGAL_ENTITY = 1;
    /** Тип - Индивидуальный предприниматель */
    const TYPE_INDIVIDUAL_ENTREPRENEUR = 2;
    /** Тип - Физ. лицо */
    const TYPE_INDIVIDUAL = 3;

    /** Документ - отсутствует */
    const DOC_EMPTY = 0;
    /** Документ - Паспорт РФ */
    const DOC_PASSPORT = 1;
    /** Документ - Отличный от Паспорта РФ */
    const DOC_NO_PASSPORT = 2;


    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return 'h_company';
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
            new HashSum("hash"),
            new StringField("name"),
            new EnumField("type", [
                'values' => [
                    self::TYPE_LEGAL_ENTITY,
                    self::TYPE_INDIVIDUAL_ENTREPRENEUR,
                    self::TYPE_INDIVIDUAL
                ],
            ]),
            new StringField("type_form"),
            new IntegerField("kind_activity_id"),
            new ReferenceField("kind_activity", __NAMESPACE__ . "\\KindOfActivityTable", ["=this.kind_activity_id" => "ref.id"]),
            new StringField("inn"),
            new StringField("kpp"),
            new DateField("date_fact_address"),
            new IntegerField("chief_contact_id"),
            new ReferenceField("chief_contact", __NAMESPACE__ . "\\ContactTable", ["=this.chief_contact_id" => "ref.id"]),
            new StringField("certificate_number"),
            new DateField("certificate_date"),
            new EnumField("doc_pass", [
                'values' => [
                    self::DOC_EMPTY,
                    self::DOC_PASSPORT,
                    self::DOC_NO_PASSPORT,
                ]
            ]),
            new StringField("doc_serial"),
            new StringField("doc_number"),
            new StringField("doc_ufms"),
            new DateField("doc_date"),
            new BooleanField("is_active"),
            new ReferenceField("main_payment_account", __NAMESPACE__ . "\\PaymentAccountRelationTable", ["=this.id" => "ref.owner_id", "=ref.is_main" => new SqlExpression('?i', true), "=ref.owner_type" => new SqlExpression('?i', PaymentAccountRelationTable::OWNER_TYPE_CLIENT_COMPANY)]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_hash", ["hash" => 40]),
            new Index("idx_guid_id", ["guid_id" => 36]),
            new Index("idx_company_entity_most", ['kind_activity_id', 'chief_contact_id']),
            new Index('idx_type', ['type']),
            new Index('idx_doc_pass', ['doc_pass']),
            new Index('idx_is_active', ['is_active'])
        ];
    }

    public static function showFullName($company, $prefix = '')
    {
        return vsprintf("%s, ИНН %s, КПП %s", [$company[$prefix . "name"], $company[$prefix . "inn"], $company[$prefix . "kpp"]]);
    }

    public static function showName($company, $prefix = '')
    {
        return vsprintf("%s", [$company[$prefix . "name"]]);
    }

	static function putTo1c($primary)
	{
		self::publishToRabbit(new CompanyExchange(), new Company([self::getRowById($primary)]));
	}

    public static function onBeforeAdd(Event $event)
    {
        $fields = $event->getParameter("fields");
        $result = new EventResult();
        $result->modifyFields([
            'hash' => $hash = sha1(implode("|", [
                mb_strtolower($fields['type']),
                mb_strtolower($fields['name']),
                mb_strtolower($fields['inn']),
            ]))
        ]);
        return $result;
    }

    public static function onAfterAdd(Event $event)
    {
        self::putTo1c($event->getParameter("id"));
	}
}
