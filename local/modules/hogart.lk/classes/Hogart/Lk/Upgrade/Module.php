<?php

namespace Hogart\Lk\Upgrade;

class Module
{
    private static $localeLoaded = false;

    public static function isWin1251()
    {
        return (defined('BX_UTF') && BX_UTF === true) ? 0 : 1;
    }

    /**
     * @return \CDatabase
     */
    public static function getDb()
    {
        return $GLOBALS['DB'];
    }

    public static function getDbName()
    {
        return $GLOBALS['DBName'];
    }


    public static function isMssql()
    {
        return ($GLOBALS['DBType'] == 'mssql');
    }

    public static function getDbOption($name, $default = '')
    {
        return \COption::GetOptionString('hogart.lk', $name, $default);
    }

    public static function setDbOption($name, $value)
    {
        if ($value != \COption::GetOptionString('hogart.lk', $name, '')) {
            \COption::SetOptionString('hogart.lk', $name, $value);
        }
    }

    protected static function getDocRoot()
    {
        return rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR);
    }

    protected static function getPhpInterfaceDir()
    {
        if (is_dir(self::getDocRoot() . '/local/php_interface')) {
            return self::getDocRoot() . '/local/php_interface';
        } else {
            return self::getDocRoot() . '/bitrix/php_interface';
        }
    }

    public static function getModuleDir()
    {
        if (is_file(self::getDocRoot() . '/local/modules/hogart.lk/include.php')) {
            return self::getDocRoot() . '/local/modules/hogart.lk';
        } else {
            return self::getDocRoot() . '/bitrix/modules/hogart.lk';
        }
    }

    public static function getUpgradeDir()
    {
        return self::getModuleDir() . '/upgrades';
    }

    public static function loadLocale($loc)
    {
        global $MESS;

        if (!self::$localeLoaded) {
            foreach ($loc as $key => $msg) {
                if (self::isWin1251()) {
                    $msg = iconv('utf-8', 'windows-1251//IGNORE', $msg);
                }
                $MESS[$key] = $msg;
            }
        }

    }
}



