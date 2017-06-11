<?php

namespace components\helpers;

class DateTimeHelper
{
    public static function offset(int $offset, $date = null, $format = 'Y-m-d H:i:s')
    {
        if($date === null) {
            $date = date($format);
        } elseif(is_numeric($date)) {
            $date = date($format, $date);
        }

        $dt = strtotime($offset);
        return $date($format, $dt);
    }

    public static function randomDate($minDate, $maxDate, $timestamp = false, $format = 'Y-m-d H:i:s')
    {
        $rand = mt_rand(self::strToTime($minDate), self::strToTime($maxDate));

        if($timestamp) {
            return $rand;
        }

        if(!$format) {
            $format = 'Y-m-d H:i:s';
        }

        return date($format, $rand);
    }

    public static function getDayOfMonth($date = null)
    {
        if($date === null) {
            $date = time();
        }
        $dateTime = self::strToTime($date);
        if(!$dateTime) {
            return false;
        }

        return date('j', $dateTime);
    }

    public static function getDaysInMonth($date)
    {
        $dateTime = self::strToTime($date);
        if(!$dateTime) {
            return false;
        }

        return date('t', $dateTime);
    }

    /**
     * Check, is $date previous month
     *
     * @param string|int $date
     *
     * @return bool
     */
    public static function isPreviousMonth($date)
    {
        $dateTime = self::strToTime($date);
        if(!$dateTime) {
            return false;
        }

        return date('m/Y', strtotime('-1 month')) == date('m/Y', $dateTime);
    }

    /**
     * @param mixed $date
     *
     * @return false|int
     */
    public static function strToTime($date)
    {
        if(empty($date)) {
            return false;
        }
        if(!is_numeric($date)) {
            $date = strtotime($date);
        }

        return $date;
    }


    //    public static function toDateTime($date, $format = 'd.m.Y | H:i')
    //    {
    //        if(!($date = self::strToTime($date))) {
    //            return $date;
    //        }
    //
    //        return date($format, $date);
    //    }

    /**
     * Возвращает текущее время, которая правильно сформирована для вставки в timestamp
     *
     * @param string $format
     *
     * @return string
     */
    //    public static function now($format = 'Y-m-d H:i:s')
    //    {
    //        return self::toDateTime(time(), $format);
    //    }

}