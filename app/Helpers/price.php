<?php

if (!function_exists('format_price')) {
    /**
     * Format the price with currency symbol and separators, the default is indonesian 'Rp.'
     *
     * @param float $price The price to format
     * @param string $currency The currency symbol
     * @param int $decimal The number of decimal points
     * @param string $decimalSeparator The character used as decimal separator
     * @param string $thousandSeparator The character used as thousand separator
     * @return string The formatted price
     */
    function format_price($price, $currency = 'Rp. ', $decimal = 0, $decimalSeparator = ',', $thousandSeparator = '.')
    {
        return $currency . number_format($price, $decimal, $decimalSeparator, $thousandSeparator);
    }
}
