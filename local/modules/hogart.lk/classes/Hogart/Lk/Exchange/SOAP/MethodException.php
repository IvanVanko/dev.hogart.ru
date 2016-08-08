<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 13:02
 */

namespace Hogart\Lk\Exchange\SOAP;


use Exception;

class MethodException extends \RuntimeException
{
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
        self::ERROR_UNDEFINED => "Неизвестная ошибка",
        self::ERROR_USER_CREATE => "Ошибка создания пользователя %s: %s",
        self::ERROR_NO_CHIEF => "Не найден Руководитель (Comp_ID_Chief) %s",
        self::ERROR_NO_CLIENT_COMPANY => "Не найдена Компания клиента %s",
        self::ERROR_NO_ITEM => "Не найдена позиция %s, порядковый номер записи - %s",
        self::ERROR_NO_ORDER => "Не найден Заказ %s",
        self::ERROR_NO_HOGART_COMPANY => "Не найдена Компания Хогарт %s",
        self::ERROR_NO_CONTRACT => "Не найден Договор %s",
        self::ERROR_NO_STORE => "Не найден Склад %s",
        self::ERROR_NO_RTU => "Не найден RTU %s",
        self::ERROR_NO_ANY_COMPANY => "Не найдена Компания Хогарт или Компания клиента %s"

    ];

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link http://php.net/manual/en/exception.construct.php
     * @param int $code [optional] The Exception code.
     * @param array $args
     * @param Exception $previous [optional] The previous exception used for the exception chaining. Since 5.3.0
     * @since 5.1.0
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