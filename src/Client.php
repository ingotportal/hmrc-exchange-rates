<?php

namespace LukaPeharda\HmrcExchangeRates;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

use SimpleXMLElement;

use LukaPeharda\HmrcExchangeRates\Exceptions\FileNotFound;

class Client
{
    /**
     * HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * HMRC Exchange Rates XML files base URL.
     *
     * @var string
     */
    protected $baseUrl = 'http://www.hmrc.gov.uk/softwaredevelopers/rates/';

    public function __construct()
    {
        $this->client = new GuzzleClient();
    }

    /**
     * Fetch montly exchange rates for given month and year.
     *
     * If year and/or month are not provided current ones will be used.
     *
     * @param   int  $year
     * @param   int  $month
     *
     * @return  SimpleXMLElement
     */
    public function fetchMonthlyRates($year = null, $month = null)
    {
        $year = $year ?? date('y');
        $month = $month ?? date('m');

        $exchangeRatesFileUrl = $this->baseUrl . 'exrates-monthly-' . $month . $year . '.xml';

        try {
            $response = $this->client->request('GET', $exchangeRatesFileUrl);
        } catch (ClientException $exception) {
            // Catch 404 instance and throw custom exception
            if ($exception->getResponse()->getStatusCode() === 404) {
                throw new FileNotFound("XML file with exchange rates for year $month/$year.");
            }

            throw $exception;
        }

        return new SimpleXMLElement($response->getBody());
    }
}
