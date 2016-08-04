<?php
/**
 * By: Ivan Kiselev aka shaqito[at]gmail.com
 * Via: PhpStorm.
 * At: 03.08.2016 22:19
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Entity\CompanyDiscountTable;
use Hogart\Lk\Entity\CompanyTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Bitrix\Main\UserTable;
use Bitrix\Main\Entity\UpdateResult;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\DB\SqlExpression;

/**
 * Class CompanyDiscount - добавление скидок Компании на товары
 * @package Hogart\Lk\Exchange\SOAP\Method
 */
class CompanyDiscount extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "CompanyDiscount";
    }

    public function getCompanyDiscounts()
    {
        return $this->client->getSoapClient()->CompanyDiscountGet(new Request());
    }

    public function companyDiscountAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->CompanyDiscountAnswer($response);
        }
    }

    public function updateCompanyDiscounts()
    {
        $answer = new Response();
        $response = $this->getCompanyDiscounts();

        foreach ($response->return->Company_Discount as $company_discount) {
            // получаем компанию пользователя
            $company = CompanyTable::getList([
                'filter' => [
                    '=guid_id' => $company_discount->Discount_ID_Company
                ]
            ])->fetch();

            // получаем товар
            $item = ElementTable::getList([
                'filter'=>[
                    '=XML_ID' => $company_discount->Discount_ID_Item,
                    '=ref.IBLOCK_ID' => new SqlExpression('?i', CATALOG_IBLOCK_ID)
                ]
            ])->fetch();

            $result = CompanyDiscountTable::createOrUpdateByField([
                'guid_id' => $company_discount->Company_Discount_ID, // @todo нет в структуре, попросить что бы добавили
                'company_id' => $company['id'],
                'item_id' => $item['ID'],
                'discount' => $company_discount->Discount,
            ], 'guid_id');

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($company_discount->Company_Discount_ID, new MethodException($error->getMessage(), intval($error->getCode()))));
                $this->client->getLogger()->error($error->getMessage() . " (" . $error->getCode() . ")");
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Скидка Компании {$result->getId()} ({$company_discount->Company_Discount_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Скидка Компании {$result->getId()} ({$company_discount->Company_Discount_ID})");
                    }
                    $answer->addResponse(new ResponseObject($company_discount->Company_Discount_ID));
                } else {
                    $answer->addResponse(new ResponseObject($company_discount->Company_Discount_ID, new MethodException(self::$default_errors[self::ERROR_UNDEFINED], self::ERROR_UNDEFINED)));
                    $this->client->getLogger()->error(self::$default_errors[self::ERROR_UNDEFINED] . " (" . self::ERROR_UNDEFINED . ")");
                }
            }
        }
        $this->companyDiscountAnswer($answer);
        return count($answer->Response);
    }
}