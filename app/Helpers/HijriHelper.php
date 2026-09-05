<?php

namespace App\Helpers;

use Carbon\Carbon;
use IntlDateFormatter;

class HijriHelper
{
    /**
     * Nama-nama bulan Hijriah dalam Bahasa Indonesia
     */
    protected static array $hijriMonths = [
        1  => ['name' => 'Muharram',        'short' => 'Muh'],
        2  => ['name' => 'Safar',           'short' => 'Saf'],
        3  => ['name' => "Rabi'ul Awwal",   'short' => 'Rob'],
        4  => ['name' => "Rabi'ul Akhir",   'short' => 'Rob II'],
        5  => ['name' => 'Jumadil Awwal',   'short' => 'Jum I'],
        6  => ['name' => 'Jumadil Akhir',   'short' => 'Jum II'],
        7  => ['name' => 'Rajab',           'short' => 'Raj'],
        8  => ['name' => "Sya'ban",         'short' => 'Sya'],
        9  => ['name' => 'Ramadhan',        'short' => 'Ram'],
        10 => ['name' => 'Syawal',          'short' => 'Syaw'],
        11 => ['name' => "Dzulqa'dah",      'short' => 'Dzulq'],
        12 => ['name' => 'Dzulhijjah',      'short' => 'Dzulh'],
    ];

    /**
     * Konversi tanggal Masehi ke Hijriah
     *
     * @param Carbon|string|int $date
     * @param int $adjustment Koreksi hilal (-2 s/d +2 hari)
     * @return array
     */
    public static function convert($date, int $adjustment = 0): array
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        if ($adjustment !== 0) {
            $date = $date->copy()->addDays($adjustment);
        }

        // Coba menggunakan IntlDateFormatter jika tersedia
        if (class_exists('IntlDateFormatter') && extension_loaded('intl')) {
            try {
                $formatter = new IntlDateFormatter(
                    'id_ID@calendar=islamic-umalqura',
                    IntlDateFormatter::FULL,
                    IntlDateFormatter::NONE,
                    'Asia/Jakarta',
                    IntlDateFormatter::TRADITIONAL,
                    'd-M-y'
                );
                
                $result = $formatter->format($date->timestamp);
                if ($result) {
                    $parts = explode('-', $result);
                    if (count($parts) >= 3) {
                        $hDay = (int) $parts[0];
                        $hMonth = (int) $parts[1];
                        $hYear = (int) $parts[2];

                        $monthInfo = self::$hijriMonths[$hMonth] ?? ['name' => 'Bulan '.$hMonth, 'short' => 'B'.$hMonth];

                        return [
                            'day' => $hDay,
                            'month_number' => $hMonth,
                            'month_name' => $monthInfo['name'],
                            'month_short' => $monthInfo['short'],
                            'year' => $hYear,
                            'formatted' => $hDay . ' ' . $monthInfo['short'],
                            'full' => $hDay . ' ' . $monthInfo['name'] . ' ' . $hYear . ' H',
                        ];
                    }
                }
            } catch (\Throwable $e) {
                // Lanjut ke fallback algoritma
            }
        }

        // Fallback Algoritma Matematika Hijriah (Standard Umm al-Qura / Tabular)
        return self::calculateFallback($date);
    }

    /**
     * Format ringkas Hijriah untuk kotak kalender (contoh: "18 Rob")
     */
    public static function getShortFormatted($date, int $adjustment = 0): string
    {
        $res = self::convert($date, $adjustment);
        return $res['formatted'];
    }

    /**
     * Fallback perhitungan matematika kalender Islam tabular
     */
    protected static function calculateFallback(Carbon $date): array
    {
        $y = $date->year;
        $m = $date->month;
        $d = $date->day;

        if (($y > 1582) || ($y === 1582 && $m > 10) || ($y === 1582 && $m === 10 && $d > 14)) {
            $jd = (int) ((1461 * ($y + 4800 + (int) (($m - 14) / 12))) / 4)
                + (int) ((367 * ($m - 2 - 12 * (int) (($m - 14) / 12))) / 12)
                - (int) ((3 * (int) (($y + 4900 + (int) (($m - 14) / 12)) / 100)) / 4)
                + $d - 32075;
        } else {
            $jd = 367 * $y - (int) ((7 * ($y + 5001 + (int) (($m - 9) / 7))) / 4)
                + (int) ((275 * $m) / 9) + $d + 1729777;
        }

        $l = $jd - 1948440 + 10632;
        $n = (int) (($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = ((int) ((10985 - $l) / 5316)) * ((int) ((50 * $l) / 17719))
            + ((int) ($l / 5670)) * ((int) ((43 * $l) / 15238));
        $l = $l - ((int) ((30 - $j) / 15)) * ((int) ((17719 * $j) / 50))
            - ((int) ($j / 16)) * ((int) ((15238 * $j) / 43)) + 29;
        
        $hMonth = (int) ((24 * $l) / 709);
        $hDay = $l - (int) ((709 * $hMonth) / 24);
        $hYear = 30 * $n + $j - 30;

        $hMonth = max(1, min(12, $hMonth));
        $monthInfo = self::$hijriMonths[$hMonth] ?? ['name' => 'Bulan '.$hMonth, 'short' => 'B'.$hMonth];

        return [
            'day' => $hDay,
            'month_number' => $hMonth,
            'month_name' => $monthInfo['name'],
            'month_short' => $monthInfo['short'],
            'year' => $hYear,
            'formatted' => $hDay . ' ' . $monthInfo['short'],
            'full' => $hDay . ' ' . $monthInfo['name'] . ' ' . $hYear . ' H',
        ];
    }
}
