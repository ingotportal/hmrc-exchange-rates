<?php

namespace LukaPeharda\HmrcExchangeRates;

use LukaPeharda\HmrcExchangeRates\Exceptions\CurrencyNotFound;

class Hmrc
{
    /**
     * HMRC Exchange Rates client
     *
     * @var \LukaPeharda\HmrcExchangeRates\Client
     */
    protected $client;

    /**
     * Location where XML files will be stored.
     *
     * @var string
     */
    protected $storagePath = null;

    /**
     * Init service
     *
     * @param   string|null  $storagePath
     *
     * @return  void
     */
    public function __construct($storagePath = null)
    {
        $this->client = new Client();
        $this->storagePath = $storagePath ?? '/tmp';
    }

    /**
     * Get monthly rates.
     *
     * @param   int  $year
     * @param   int  $month
     * @param   bool  $useCache
     *
     * @return  array
     */
    public function getMonthlyRates($year = null, $month = null, $useCache = true)
    {
        $year = $year ?? date('y');
        $month = $month ?? date('m');

        $filePath = rtrim($this->storagePath, '/') . '/rates-' . $month . $year . '.xml';

        $xmlHandler = new XmlHandler();

        if ($useCache && file_exists($filePath)) {
            $xml = $xmlHandler->loadFromFile($filePath);
        } else {
            $xml = $this->client->fetchMonthlyRates($year, $month);

            $xmlHandler->saveToFile($xml, $filePath);
        }

        return $xmlHandler->convertXmlToArray($xml);
    }


    /**
     * Return monthly exchange rate for given currency
     *
     * @param   string  $currency  ISO 4271 format
     * @param   int  $year
     * @param   int  $month
     * @param   bool  $useCache
     *
     * @return  float
     */
    public function getMonthlyRateForCurrency($currency, $year = null, $month = null, $useCache = true)
    {
        $year = $year ?? date('y');
        $month = $month ?? date('m');

        $rates = $this->getMonthlyRates($year, $month, $useCache);

        $currency = strtoupper($currency);

        if (array_key_exists($currency, $rates) === false) {
            throw new CurrencyNotFound("Currency $currency not found for $month/$year.");
        }

        return $rates[$currency];
    }
}
