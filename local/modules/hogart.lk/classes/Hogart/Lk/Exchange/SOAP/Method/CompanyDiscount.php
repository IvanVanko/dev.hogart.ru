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
use Hogart\Lk\Exchange\SOAP\MethodException;
use Hogart\Lk\Exchange\SOAP\Request;
use Hogart\Lk\Exchange\SOAP\Response;
use Hogart\Lk\Exchange\SOAP\ResponseObject;

/**
 * Class CompanyDiscount - добавление скидок Компании на товары
 * @package Hogart\Lk\Exchange\SOAP\Method
 */
class CompanyDiscount extends AbstractMethod
{
    /**
     * {@inheritDoc}
     */
    function getName()
    {
        return "CompanyDiscount";
    }

    public function getCompanyDiscounts()
    {
        return $this->client->getSoapClient()->DiscountGet(new Request());
    }

    public function companyDiscountAnswer(Response $response)
    {
        if (count($response->Response) && $this->is_answer) {
            return $this->client->getSoapClient()->DiscountAnswer($response);
        }
    }

    public function updateCompanyDiscounts()
    {
        $answer = new Response();
        $response = $this->getCompanyDiscounts();

        foreach ($response->return->Discount as $k => $discount) {
            // получаем компанию пользователя
            $company = CompanyTable::getByField('guid_id', $discount->Discount_ID_Company);
            if(!isset($company)){
                $answer->addResponse(new ResponseObject(
                    $discount->Discount_ID_Company . '_' . $discount->Discount_ID_Item, new MethodException(
                        MethodException::ERROR_NO_CLIENT_COMPANY,
                        [$discount->Discount_ID_Company]
                    )
                ));
                continue;
            }
            // получаем товар
            $item = ElementTable::getList([
                'filter'=>[
                    '=XML_ID' => $discount->Discount_ID_Item,
                    '=IBLOCK.ID' => new SqlExpression('?i', CATALOG_IBLOCK_ID)
                ]
            ])->fetch();

            if (!isset($item)) {
                $n = $k + 1;
                $answer->addResponse(new ResponseObject($discount->Company_Discount_ID, new MethodException(MethodException::ERROR_NO_ITEM, [$discount->Discount_ID_Item, $n])));
                continue;
            }

            $result = CompanyDiscountTable::createOrUpdateByField([
                'company_id' => $company['id'],
                'item_id' => $item['ID'],
                'discount' => $discount->Discount_Value,
            ], ['=company_id' => $company['id'], '=item_id' => $item['ID']]);

            if ($result->getErrorCollection()->count()) {
                $error = $result->getErrorCollection()->current();
                $answer->addResponse(new ResponseObject($discount->Company_Discount_ID, new MethodException(MethodException::ERROR_BITRIX, [$error->getMessage(), $error->getCode()], $error)));
            } else {
                if ($result->getId()) {
                    if ($result instanceof UpdateResult) {
                        $this->client->getLogger()->notice("Обновлена запись Скидка Компании {$result->getId()} ({$discount->Company_Discount_ID})");
                    } else {
                        $this->client->getLogger()->notice("Добавлена запись Скидка Компании {$result->getId()} ({$discount->Company_Discount_ID})");
                    }
                    $answer->addResponse(new ResponseObject($discount->Company_Discount_ID));
                } else {
                    $answer->addResponse(new ResponseObject($discount->Company_Discount_ID, new MethodException(MethodException::ERROR_UNDEFINED)));
                }
            }
        }
        $this->companyDiscountAnswer($answer);
        return count($answer->Response);
    }
}