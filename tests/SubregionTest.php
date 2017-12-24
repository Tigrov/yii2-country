<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Subregion;

class SubregionTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('151', Subregion::create('151')->code);
    }

    public function testName()
    {
        $this->assertSame('Eastern Europe', Subregion::create('151')->name);
        $this->assertSame('Northern America', Subregion::create('021')->name);
        $this->assertSame('Eastern Asia', Subregion::create('030')->name);
    }

    public function testRegionCode()
    {
        $this->assertEquals('150', Subregion::create('151')->regionCode);
        $this->assertEquals('019', Subregion::create('021')->regionCode);
        $this->assertEquals('142', Subregion::create('030')->regionCode);
    }

    public function testRegionName()
    {
        $this->assertSame('Europe', Subregion::create('151')->regionName);
        $this->assertSame('Americas', Subregion::create('021')->regionName);
        $this->assertSame('Asia', Subregion::create('030')->regionName);
    }

    public function testRegion()
    {
        $this->assertSame('Europe', Subregion::create('151')->region->name);
        $this->assertSame('Americas', Subregion::create('021')->region->name);
        $this->assertSame('Asia', Subregion::create('030')->region->name);
    }

    public function testCountryCodes()
    {
        $this->assertEquals(['BG','BY','CZ','HU','MD','PL','RO','RU','SK','UA'],
            Subregion::create('151')->countryCodes);
    }

    public function testCountryNames()
    {
        $countryNames = Subregion::create('151')->countryNames;
        $this->assertArraySubset(['RU' => 'Russia', 'CZ' => 'Czech Republic'], $countryNames);
        $this->assertFalse(isset($countryNames['DE']));
    }

    public function testCountries()
    {
        $countries = Subregion::create('151')->countries;
        $this->assertSame('Russia', $countries['RU']->name);
        $this->assertSame('Czech Republic', $countries['CZ']->name);
        $this->assertFalse(isset($countries['DE']));
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\Subregion', Subregion::intldataClassName());
    }
}