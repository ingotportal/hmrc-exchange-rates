# HM Revenue & Customs Exchange Rates client

Fetch monthly exchange rates from HMRC website using PHP. Rates are parsed from [monthly XML files](http://www.hmrc.gov.uk/softwaredevelopers/2019-exrates.html).

Fetched XML files are storage in a provided path to optimise and speed up the process.


## Installation

Install it using composer:

`composer require lukapeharda/hmrc-exchange-rates`


## Usage

Fetch all currency / exchange rates value pairs:

```php
<?php

// Initialize the client and provide storage path for file cache.
// If path isn't provided "/tmp" will be used.
$hmrcClient = new LukaPeharda\HmrcExchangeRates\Hmrc('/storage/path');

// First param is a two digit representation of a year (19). Second one is a
// numeric representation of a month (05) with leading zeros. And using
// third one you can decide if you want to use cached values or force new
// ones to be fetched.

// Only first param is required. Others are optional. Current year and month
// will be used and cache will be used by default.
$rates = $hmrcClient->getMonthlyRates(date('y'), date('m'), $useCache = true);

// $rates = [... , 'EUR' => 1.1545, ... , 'USD' => 1.3049, ...] at the time of
// writting documentation
```

Fetch rate for a specific currency (USD used in this example):

```php
<?php

// Initialize the client and provide storage path for file cache.
// If path isn't provided "/tmp" will be used.
$hmrcClient = new LukaPeharda\HmrcExchangeRates\Hmrc('/storage/path');

// First param is currency code in ISO 4217. Second one a two digit
// representation of a year (19). Third one is a numeric representation of a
// month (05) with leading zeros. With fourth one you can decide if you  want
// to use cached values or force new ones to be fetched.

// Only first param is required. Others are optional. Current year and month
// will be used and cache will be used by default.
$usdRate = $hmrcClient->getMonthlyRateForCurrency('USD', date('y'), date('m'), $useCache = true);

// $usdRate = 1.3049 at the time of writting documentation
```