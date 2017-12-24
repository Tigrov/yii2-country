<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Continent;

class ContinentTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('EU', Continent::create('EU')->code);
    }

    public function testName()
    {
        $this->assertSame('Europe', Continent::create('EU')->name);
        $this->assertSame('North america', Continent::create('NA')->name);
        $this->assertSame('Asia', Continent::create('AS')->name);
    }

    public function testAll()
    {
        $continents = Continent::all();
        $this->assertSame(7, count($continents));
        $this->assertSame('Europe', $continents['EU']->name);
        $this->assertSame('North america', $continents['NA']->name);
        $this->assertSame('Asia', $continents['AS']->name);
    }

    public function testCountryCodes()
    {
        $this->assertEquals([
            'CH','BG','AX','AL','AT','BY','FI','GB','GG','CY','CZ','DK','ES','EE','FO','GI','XK','HU','HR','IM',
            'IE','IS','IT','JE','NL','LI','LT','LV','MC','MD','MT','ME','SM','SE','PT','RO','SI','SJ','VA','AD',
            'FR','NO','DE','GR','LU','MK','PL','RU','UA','BE','BA','RS','SK'
        ], Continent::create('EU')->countryCodes);
    }

    public function testCountryNames()
    {
        $this->assertArraySubset(['RU' => 'Russia', 'DE' => 'Germany'], Continent::create('EU')->countryNames);
        $this->assertSame(
            ['US' => 'United States', 'CN' => 'China'],
            array_diff_assoc(
                ['RU' => 'Russia', 'DE' => 'Germany', 'US' => 'United States', 'CN' => 'China'],
                Continent::create('EU')->countryNames
            )
        );
    }

    public function testCountries()
    {
        $countries = Continent::create('EU')->countries;
        $this->assertSame('RU', $countries['RU']->code);
        $this->assertSame('DE', $countries['DE']->code);
        $this->assertFalse(isset($countries['US']));
        $this->assertFalse(isset($countries['CN']));
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\Continent', Continent::intldataClassName());
    }
}