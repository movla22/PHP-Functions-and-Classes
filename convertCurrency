<?php 

function getExchangeRate($currency) {
    $url = 'https://www.tcmb.gov.tr/kurlar/today.xml';
    $xml = simplexml_load_file($url);
    $exchangeRates = [];
    foreach ($xml->Currency as $currencyData) {
        $code = (string) $currencyData->attributes()->CurrencyCode;
        $exchangeRates[$code] = (float) $currencyData->ForexBuying;
    }
    return $exchangeRates[$currency];
}

function convertCurrency($amount, $fromCurrency, $toCurrency = 'TRY') {
    $exchangeRate = getExchangeRate($fromCurrency);
    return $amount * $exchangeRate;
}


$amount = 100;
$fromCurrency = 'EUR';
$result = convertCurrency($amount, $fromCurrency);
echo "$amount $fromCurrency = $result TRY";
?>
