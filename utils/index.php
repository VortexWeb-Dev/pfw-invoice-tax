<?php

function getTaxes($totalTax) {
    $sgst = $totalTax / 2;
    $cgst = $totalTax / 2;

    return [
        'sgst' => $sgst,
        'cgst' => $cgst,
    ];
}

function numberToWords($number) {
    static $dictionary = [
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'forty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
    ];

    if (!is_numeric($number)) {
        return false;
    }

    if ($number < 0) {
        return 'negative ' . numberToWords(abs($number));
    }

    $string = '';
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    } else {
        $fraction = null;
    }

    if ($number < 21) {
        $string = $dictionary[$number];
    } elseif ($number < 100) {
        $tens = (int) ($number / 10) * 10;
        $units = $number % 10;
        $string = $dictionary[$tens];
        if ($units) {
            $string .= '-' . $dictionary[$units];
        }
    } elseif ($number < 1000) {
        $hundreds = (int) ($number / 100);
        $remainder = $number % 100;
        $string = $dictionary[$hundreds] . ' hundred';
        if ($remainder) {
            $string .= ' and ' . numberToWords($remainder);
        }
    } else {
        $baseUnit = pow(1000, floor(log($number, 1000)));
        $numBaseUnits = (int) ($number / $baseUnit);
        $remainder = $number % $baseUnit;
        $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
        if ($remainder) {
            $string .= $remainder < 100 ? ' and ' : ', ';
            $string .= numberToWords($remainder);
        }
    }

    if ($fraction !== null && is_numeric($fraction)) {
        $string .= ' point ';
        $words = [];
        foreach (str_split($fraction) as $digit) {
            $words[] = $dictionary[$digit];
        }
        $string .= implode(' ', $words);
    }

    return strtoupper($string);
}
