<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 30/09/2016
 * Time: 19:22
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Bitrix\Currency\CurrencyTable;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\Request;

class CurrencyRate extends AbstractMethod
{
    /**
     * @return string
     */
    function getName()
    {
        return "CurrencyRate";
    }

    public function rateGet()
    {
        $currencies = \CStorage::getVar('HOGART.CURRENCIES');
        $request = [];
        foreach ($currencies as $currency) {
            $request[] = (object)[
                'Currency' => $currency['CURRENCY']
            ];
        }
        return $this->client->getSoapClient()->RateGet(['Data' => $request]);
    }

    public function updateCurrencyRates()
    {
        $response = $this->rateGet();
        foreach ($response->return->Rate as $rate) {
            CurrencyTable::update($rate->Currency, [
                'AMOUNT' => (float)$rate->Rate,
                'CURRENT_BASE_RATE' => (float)$rate->Rate,
                'DATE_UPDATE' => new DateTime()
            ]);
        }
    }
}
