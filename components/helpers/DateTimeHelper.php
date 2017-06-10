<?php

namespace components\helpers;

class DateTimeHelper
{
    public static function randomDate($minDate, $maxDate, $timestamp = false, $format = 'Y-m-d H:i:s')
    {
        $minDateTime = self::strToTime($minDate);
        $maxDateTime = self::strToTime($maxDate);

        $rand = mt_rand($minDateTime, $maxDateTime);

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
        if(!is_int($date)) {
            $date = strtotime($date);
        }

        return $date;
    }


    public static function toDateTime($date, $format = 'd.m.Y | H:i')
    {
        if(!($date = self::strToTime($date))) {
            return $date;
        }

        return date($format, $date);
    }

    /**
     * Сравнение двух дат.
     * Если первая дата больше или равны => false
     * Параметры принимают либо timestamp integer либо string формата,
     * достаточном для применения для нее функции strtotime
     *
     * @param mixed $minuend
     * @param mixed $subtrahend
     *
     * @return boolean
     */
    public static function compare($minuend, $subtrahend)
    {
        $d1 = !is_int($minuend) ? strtotime($minuend) : $minuend;
        $d2 = !is_int($subtrahend) ? strtotime($subtrahend) : $subtrahend;

        return $d1 < $d2;
    }

    public static function subtractDays($date, $substrDays, $format = 'd.m.Y H:i:s')
    {
        if(!($date = self::strToTime($date))) {
            return $date;
        }

        return date($format, ($date - ($substrDays * 86400)));
    }

    /**
     * Возвращает текущее время, которая правильно сформирована для вставки в timestamp
     *
     * @param string $format
     *
     * @return string
     */
    public static function now($format = 'Y-m-d H:i:s')
    {
        return self::toDateTime(time(), $format);
    }

    /**
     * Дата для вставки в БД
     *
     * @param null|integer|string $time
     * @param string $format
     *
     * @return bool|string
     */
    public static function dbDate($time = null, $format = 'Y-m-d H:i:s')
    {
        if(!$time) {
            $time = time();
        }

        return self::toDateTime($time, $format);
    }

    /**
     * Часы в секунды
     *
     * @param float|int $hour
     *
     * @return float|int
     */
    public static function hoursToSeconds($hour)
    {
        return (int) $hour * 60 * 60;
    }

    /**
     * Дни в секунды
     *
     * @param float|int $days
     *
     * @return float|int
     */
    public static function daysToSeconds($days)
    {
        return (int) $days * 60 * 60 * 24;
    }


    public static function smartFormat($date)
    {
        if(!($date = self::strToTime($date))) {
            return $date;
        }

        $now = time();
        $today = date('d', $now);

        if(date('Y', $now) != date('Y', $date)) {
            return date('d '.self::getPluralRusMonth($date).' Y, H:i', $date);
        } elseif(date('m', $now) != date('m', $date)) {
            date('d '.self::getPluralRusMonth($date).', H:i', $date);
        } elseif($today == date('d', $date)) {
            return date('Сегодня, H:i', $date);
        } elseif(($today - date('d', $date)) == 1) {
            return date('Вчера, H:i', $date);
        }

        return date('d '.self::getPluralRusMonth($date).', H:i', $date);
    }

    /**
     * @param $date
     * @param bool $ucFirst
     *
     * @return false|string
     */
    public static function getPluralRusMonth($date, $ucFirst = false)
    {
        if(!($date = self::strToTime($date))) {
            return $date;
        }

        $month = self::rusMonth();
        $numMonth = (int) date('m', $date);
        $result = array_key_exists($numMonth, $month) ? $month[$numMonth]['plural'] : date('F', $numMonth);

        return $ucFirst ? StringHelper::ucFirstLetter($result) : $result;
    }

    /**
     * @return array
     */
    public static function rusMonth()
    {
        return [
            1  => [
                'single' => 'январь',
                'plural' => 'января',
            ],
            2  => [
                'single' => 'февраль',
                'plural' => 'февраля',
            ],
            3  => [
                'single' => 'март',
                'plural' => 'марта',
            ],
            4  => [
                'single' => 'апрель',
                'plural' => 'апреля',
            ],
            5  => [
                'single' => 'май',
                'plural' => 'мая',
            ],
            6  => [
                'single' => 'июнь',
                'plural' => 'июня',
            ],
            7  => [
                'single' => 'июль',
                'plural' => 'июля',
            ],
            8  => [
                'single' => 'август',
                'plural' => 'августа',
            ],
            9  => [
                'single' => 'сентябрь',
                'plural' => 'сентября',
            ],
            10 => [
                'single' => 'октябрь',
                'plural' => 'октября',
            ],
            11 => [
                'single' => 'ноябрь',
                'plural' => 'ноября',
            ],
            12 => [
                'single' => 'декабрь',
                'plural' => 'декабря',
            ],
        ];
    }
}