<?php

namespace App\Helpers;

use DateTime;

/**
 * Description of FormatConverter
 *
 * @author Hendri <hendri.gnw@gmail.com>
 */
class FormatConverter
{
    /**
     * Format string as date
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function dateFormat($date, $format)
    {
        $date = new DateTime($date);

        return $date->format($format);
    }

    /**
     * the date format into indonesian
     *
     * @param type $date date or datetime
     * @param type $format eg. (%Y-%m-%d)
     * @see type $format http://php.net/manual/en/function.strftime.php
     * @return type
     */
    public static function indoDateFormat($date, $format)
    {
        setLocale(LC_ALL, 'id_ID', 'ind', 'indonesia');
        return strftime($format, strtotime($date));
    }

    /**
     * Format number to rupiah
     * @param float $value
     * @return string
     */
    public static function rupiahFormat($value, $decimal = 0, $prefix = 'Rp. ')
    {
        return $prefix . number_format($value, $decimal, ',', '.');
    }

    /**
     * Format number to dollar
     * @param float $value
     * @return string
     */
    public static function dollarFormat($value, $decimal = 0)
    {
        return number_format($value, $decimal, '.', ',');
    }

    /**
     *
     * @param type $date
     * @param type $interval
     * @param type $format
     * @return type
     */
    public static function dateInIntervalFormat($date, $interval, $format = 'Y-m-d')
    {
        $date = date_create($date);
        date_add($date, date_interval_create_from_date_string($interval .' days'));

        return date_format($date, $format);
    }

    /**
     *
     * @param type $validators
     * @return type
     */
    public static function parseValidatorErrors($validators)
    {
        $result = [];
        foreach ($validators->getRules() as $key => $attribute) {
            $errors = $validators->errors()->getMessages();
            if (array_key_exists($key, $errors)) {
                $result[$key] = implode('\n', $errors[$key]);
            } else {
                $result[$key] = null;
            }
        }
        return $result;
    }

    public static function convertMinuteToHour($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function averageTime(array $times)
    {
        function calc_time($times) {
            $i = 0;
            foreach ($times as $time) {
                sscanf($time, '%d:%d:%d', $hour, $min, $sec);
                $i += $hour * 3600 + $min * 60 + $sec;
            }
            return $i;
        }

        function sum_time($times) {
            $i = calc_time($times);
            if ($h = floor($i / 3600)) $i %= 3600;
            if ($m = floor($i / 60)) $i %= 60;
            return sprintf('%02d:%02d:%02d', $h, $m, $i);
        }

        function avg_time($times) {
            $i = calc_time($times);
            $i = round($i / count($times));
            if ($h = floor($i / 3600)) $i %= 3600;
            if ($m = floor($i / 60)) $i %= 60;
            return sprintf('%02d:%02d:%02d', $h, $m, $i);
        }

        return avg_time($times);
    }

    public static function numberToWord($number)
    {
        $wordIndex = [
            '0' => 'kosong',
            '1' => 'satu',
            '2' => 'dua',
            '3' => 'tiga',
            '4' => 'empat',
            '5' => 'lima',
            '6' => 'enam',
            '7' => 'tujuh',
            '8' => 'delapan',
            '9' => 'sembilan'
        ];

        $numberInWord = '';
        $splitWord = str_split($number);

        foreach ($splitWord as $sw) {
            $numberInWord .= ($wordIndex[$sw]??$sw) . ' ';
        }

        return $numberInWord;
    }

    public static function listMonth()
    {
        $listMonth = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
            'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        foreach($listMonth as $key => $month ){
            $data['id'] = $key + 1;
            $data['name'] = $month;

            $newListMonth[] = $data;
        };

        return $newListMonth;
    }

    /**
     * collection pagination
     *
     * @param $items
     * @param null $page
     * @param int $perPage
     * @param array $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function collectionPaginate($items, $page = null, $perPage = 15, $options = []) {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
        //ref for array_values() fix: https://stackoverflow.com/a/38712699/3553367
    }


    /**
     * Rename key array associative
     *
     * @param array $array
     * @param string $prefix
     * @param string $toPrefix
     */
    public static function renameKey($array, $prefix, $toPrefix) {
        $result = [];
        foreach ($array as $key => $value) {
            $key = str_replace($prefix, $toPrefix, $key);
            $result[$key] = $value;
        }
        return $result;
    }

    public static function integerToWord($number)
    {
        $number = str_replace('.', '', $number);

        if ( ! is_numeric($number)) throw new \Exception('Error');

        $base    = array('nol', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan');
        $numeric = array('1000000000000000', '1000000000000', '1000000000000', 1000000000, 1000000, 1000, 100, 10, 1);
        $unit    = array('kuadriliun', 'triliun', 'biliun', 'milyar', 'juta', 'ribu', 'ratus', 'puluh', '');
        $str     = null;

        $i = 0;

        if ($number == 0)
        {
            $str = 'nol';
        }
        else
        {
            while ($number != 0)
            {
                $count = (int)($number / $numeric[$i]);

                if ($count >= 10)
                {
                    $str .= static::integerToWord($count) . ' ' . $unit[$i] . ' ';
                }
                elseif ($count > 0 && $count < 10)
                {
                    $str .= $base[$count] . ' ' . $unit[$i] . ' ';
                }

                $number -= $numeric[$i] * $count;

                $i++;
            }

            $str = preg_replace('/satu puluh (\w+)/i', '\1 belas', $str);
            $str = preg_replace('/satu (ribu|ratus|puluh|belas)/', 'se\1', $str);
            $str = preg_replace('/\s{2,}/', ' ', trim($str));
        }

        return $str;
    }
}
