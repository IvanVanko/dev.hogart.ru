<?php
/**
 * Created by PhpStorm.
 * User: k-malov
 * Date: 25.12.16
 * Time: 23:36
 */

namespace Hogart\Lk\Helper\Common;


class DateTime
{
    public static $DATE_ONE_SMALLER = -1;
    public static $DATE_ONE_BIGGER = 1;
    public static $DATE_ONE_EQUAL_DATE_TWO = 0;

    /**
     * @param $dateOne - date from bitrix
     * @param $dateTwo - date from bitrix
     * @param $format - str DateTime format
     * @return bool -
     */
    public static function isTwoDatesEqualAsStrings($dateOne, $dateTwo, $format) {
        return (FormatDate($format, MakeTimeStamp($dateOne)) == FormatDate($format, MakeTimeStamp($dateTwo)));
    }

    /**
     * @param $date - date from bitrix
     * @param $offset - offset in seconds
     * @return int - epoch
     */
    public static function changeDateTimeWithOffset($date, $offset) {
        return MakeTimeStamp($date) + $offset;
    }

    /**
     * @param $dateOne - date from bitrix
     * @param $dateTwo - date from bitrix
     * @return int - 1 if dateOne bigger, 0 dateOne and dateTwo are equal, -1 if dateOne smaller
     */
    public static function compareTwoEpochDates($dateOne, $dateTwo) {
        if ($dateOne < $dateTwo) {
            return DateTime::$DATE_ONE_SMALLER;
        } elseif ($dateOne == $dateTwo) {
            return DateTime::$DATE_ONE_EQUAL_DATE_TWO;
        } elseif ($dateOne > $dateTwo) {
            return DateTime::$DATE_ONE_BIGGER;
        }
    }
}