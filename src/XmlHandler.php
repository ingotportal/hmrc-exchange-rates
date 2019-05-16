<?php

namespace LukaPeharda\HmrcExchangeRates;

use DOMDocument;
use SimpleXMLElement;

class XmlHandler
{
    /**
     * Load XML from file.
     *
     * @param   string  $filePath
     *
     * @return  SimpleXMLElement
     */
    public function loadFromFile($filePath)
    {
        return simplexml_load_file($filePath);
    }

    /**
     * Save given XMl to a file path provided.
     *
     * @param   SimpleXMLElement  $simpleXml
     * @param   string            $filePath
     *
     * @return  integer
     */
    public function saveToFile(SimpleXMLElement $simpleXml, $filePath)
    {
        $dom = new DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($simpleXml->asXML());

        return $dom->save($filePath);
    }

    /**
     * Convert simple XML to array with currency code as key and rate as value.
     *
     * @param   SimpleXMLElement  $simpleXml
     *
     * @return  array
     */
    public function convertXmlToArray($simpleXml)
    {
        $rates = [];

        foreach ($simpleXml->children() as $node) {

            $rates[(string)$node->currencyCode] = (float) $node->rateNew;
        }

        return $rates;
    }
}