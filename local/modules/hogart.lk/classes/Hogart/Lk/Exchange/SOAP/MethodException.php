<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 13:02
 */

namespace Hogart\Lk\Exchange\SOAP;


use Exception;

/**
 * Класс ошибок методов SOAP
 *
 * __Ошибки, возвращаемые в ответах:__
 * | __Код ошибки__	| __Текст ошибки__                                                          	|
 * |:----------:	|--------------                                                             	|
 * |    __0__   	| _Ошибка внутри кода Битрикс: &lt;error&gt; (&lt;error-code&gt;)_          	|
 * |    __1__   	| _Неизвестная ошибка_                                                      	|
 * |    __2__   	| _Ошибка создания пользователя &lt;login&gt;: &lt;error&gt;_               	|
 * |    __3__   	| _Не найден Руководитель &lt;chief-id&gt;_                                 	|
 * |    __4__   	| _Не найдена Компания клиента &lt;company-id&gt;_                          	|
 * |    __5__   	| _Не найдена позиция &lt;item-id&gt;, порядковый номер записи - &lt;n&gt;_	|
 * |    __6__   	| _Не найден Заказ &lt;order-id&gt;_                                        	|
 * |    __7__   	| _Не найден Договор &lt;contract-id&gt;_                                   	|
 * |    __8__   	| _Не найдена Компания Хогарт &lt;hogart-company-id&gt;_                    	|
 * |    __9__   	| _Не найден Склад &lt;store-id&gt;_                                        	|
 * |    __10__   	| _Не найден Реализация товаров и услуг (отгрузка) &lt;rtu-id&gt;_          	|
 * |    __11__   	| _Не найдена Компания Хогарт или Компания клиента &lt;company-id&gt;_      	|
 * @package Hogart\Lk\Exchange\SOAP
 */
class MethodException extends \RuntimeException
{
    /** Ошибка внутри кода Битрикс */
    const ERROR_BITRIX = 0;
    /** Неизвестная ошибка */
    const ERROR_UNDEFINED = 1;
    /** Ошибка создания пользователя */
    const ERROR_USER_CREATE = 2;
    /** Не найден Руководитель */
    const ERROR_NO_CHIEF = 3;
    /** Не найдена Компания клиента */
    const ERROR_NO_CLIENT_COMPANY = 4;
    /** Не найдена позиция */
    const ERROR_NO_ITEM = 5;
    /** Не найден Заказ */
    const ERROR_NO_ORDER = 6;
    /** Не найден Договор */
    const ERROR_NO_CONTRACT = 7;
    /** Не найдена Компания Хогарт */
    const ERROR_NO_HOGART_COMPANY = 8;
    /** Не найден Склад */
    const ERROR_NO_STORE = 9;
    /** Не найден RTU */
    const ERROR_NO_RTU = 10;
    /** Не найдена Компания Хогарт или Компания клиента */
    const ERROR_NO_ANY_COMPANY = 11;

    protected static $errors = [
        self::ERROR_BITRIX => "Ошибка внутри кода Битрикс: %s (%s)",
        self::ERROR_UNDEFINED => "Неизвестная ошибка",
        self::ERROR_USER_CREATE => "Ошибка создания пользователя %s: %s",
        self::ERROR_NO_CHIEF => "Не найден Руководитель (Comp_ID_Chief) %s",
        self::ERROR_NO_CLIENT_COMPANY => "Не найдена Компания клиента %s",
        self::ERROR_NO_ITEM => "Не найдена позиция %s, порядковый номер записи - %s",
        self::ERROR_NO_ORDER => "Не найден Заказ %s",
        self::ERROR_NO_CONTRACT => "Не найден Договор %s",
        self::ERROR_NO_HOGART_COMPANY => "Не найдена Компания Хогарт %s",
        self::ERROR_NO_STORE => "Не найден Склад %s",
        self::ERROR_NO_RTU => "Не найден Реализация товаров и услуг (отгрузка) %s",
        self::ERROR_NO_ANY_COMPANY => "Не найдена Компания Хогарт или Компания клиента %s"

    ];

    /**
     * Конструктор ошибки метода
     *
     * @param int|string $code Код ошибки из предопределенных или сообщение об ошибке.
     * @param array|int $args Заменяемые в шаблоне ошибки параметры или код ошибки.
     * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     */
    public function __construct($code, $args = [], Exception $previous)
    {
        if (!is_int($code) || !($message = static::getErrorMessage($code, $args))) {
            $message = $code;
            $code = $args ? : 0;
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * Получить ошибку из предопределенных
     * @param $code
     * @param array $args
     * @return string
     */
    protected static function getErrorMessage($code, $args = [])
    {
        return vsprintf(static::$errors[$code], $args);
    }
}