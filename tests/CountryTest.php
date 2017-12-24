<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Country;

class CountryTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('RU', Country::create('RU')->code);
    }

    public function testName()
    {
        $this->assertSame('Russia', Country::create('RU')->name);
        $this->assertSame('United States', Country::create('US')->name);
        $this->assertSame('China', Country::create('CN')->name);
    }

    public function testAll()
    {
        $countries = Country::all();
        $this->assertSame(250, count($countries));
        $this->assertSame('Russia', $countries['RU']->name);
        $this->assertSame('United States', $countries['US']->name);
    }

    public function testContinentCode()
    {
        $this->assertSame('EU', Country::create('RU')->continentCode);
        $this->assertSame('NA', Country::create('US')->continentCode);
        $this->assertSame('AS', Country::create('CN')->continentCode);
    }

    public function testRegionCode()
    {
        $this->assertEquals('150', Country::create('RU')->regionCode);
        $this->assertEquals('019', Country::create('US')->regionCode);
        $this->assertEquals('142', Country::create('CN')->regionCode);
    }

    public function testSubregionCode()
    {
        $this->assertEquals('151', Country::create('RU')->subregionCode);
        $this->assertEquals('021', Country::create('US')->subregionCode);
        $this->assertEquals('030', Country::create('CN')->subregionCode);
    }

    public function getMeasurementSystemCode()
    {
        $this->assertSame('SI', Country::create('RU')->measurementSystemCode);
        $this->assertSame('US', Country::create('US')->measurementSystemCode);
        $this->assertSame('SI', Country::create('CN')->measurementSystemCode);
    }

    public function testLocaleCodes()
    {
        $this->assertTrue(in_array('ru_RU', Country::create('RU')->localeCodes));
        $this->assertTrue(in_array('en_US', Country::create('US')->localeCodes));
    }

    public function testLocaleNames()
    {
        $this->assertArraySubset(['ru_RU' => 'Russian (Russia)'], Country::create('RU')->localeNames);
        $this->assertArraySubset(['en_US' => 'English (United States)'], Country::create('US')->localeNames);
    }

    public function testLocales()
    {
        $this->assertSame('ru_RU', Country::create('RU')->locales['ru_RU']->code);
        $this->assertSame('en_US', Country::create('US')->locales['en_US']->code);
    }

    public function testLanguageCodes()
    {
        $this->assertTrue(in_array('ru', Country::create('RU')->languageCodes));
        $this->assertTrue(in_array('en', Country::create('US')->languageCodes));
    }

    public function testLanguageNames()
    {
        $this->assertArraySubset(['ru' => 'Russian'], Country::create('RU')->languageNames);
        $this->assertArraySubset(['en' => 'English'], Country::create('US')->languageNames);
    }

    public function testLanguages()
    {
        $this->assertSame('ru', Country::create('RU')->languages['ru']->code);
        $this->assertSame('en', Country::create('US')->languages['en']->code);
    }

    public function testLocaleCode()
    {
        $this->assertSame('ru_RU', Country::create('RU')->localeCode);
        $this->assertSame('en_US', Country::create('US')->localeCode);
    }

    public function testLanguageCode()
    {
        $this->assertSame('ru', Country::create('RU')->languageCode);
        $this->assertSame('ru', Country::create('RU')->language_code);
        $this->assertSame('en', Country::create('US')->languageCode);
        $this->assertSame('en', Country::create('US')->language_code);
    }

    public function testCurrencyCode()
    {
        $this->assertSame('RUB', Country::create('RU')->currencyCode);
        $this->assertSame('RUB', Country::create('RU')->currency_code);
        $this->assertSame('USD', Country::create('US')->currencyCode);
        $this->assertSame('USD', Country::create('US')->currency_code);
    }

    public function testTimezoneCode()
    {
        $this->assertSame('Europe/Moscow', Country::create('RU')->timezoneCode);
        $this->assertSame('Europe/Moscow', Country::create('RU')->timezone_code);
        $this->assertSame('America/New_York', Country::create('US')->timezoneCode);
        $this->assertSame('America/New_York', Country::create('US')->timezone_code);
    }

    public function testDivisions()
    {
        $this->assertSame('California', Country::create('US')->divisions['CA']->name);
    }

    public function testCities()
    {
        $cities = Country::create('AM')->cities;
        $this->assertSame('Yerevan', $cities[616052]->name);
        // New York
        $this->assertFalse(isset($cities[5128581]));
    }

    public function testCapital()
    {
        $this->assertSame('Moscow', Country::create('RU')->capital->name);
        $this->assertSame('Washington D.C.', Country::create('US')->capital->name);
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\Country', Country::intldataClassName());
    }

    public function testRinvex()
    {
        if (PHP_MAJOR_VERSION >= 7) {
            $this->assertSame('.ru', Country::create('RU')->rinvex->getTld());
            $this->assertSame('.us', Country::create('US')->rinvex->getTld());
        }
    }
}