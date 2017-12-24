<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Region;

class RegionTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('150', Region::create('150')->code);
    }

    public function testName()
    {
        $this->assertSame('Europe', Region::create('150')->name);
        $this->assertSame('Americas', Region::create('019')->name);
        $this->assertSame('Asia', Region::create('142')->name);
    }

    public function testSubregionCodes()
    {
        $this->assertEquals(['154','039','155','151'], Region::create('150')->subregionCodes);
        $this->assertEquals(['029','005','013','021'], Region::create('019')->subregionCodes);
    }

    public function testSubregionNames()
    {
        $this->assertEquals([151 => 'Eastern Europe', 154 => 'Northern Europe', '039' => 'Southern Europe', 155 => 'Western Europe'], Region::create('150')->subregionNames);
    }

    public function testSubregions()
    {
        $subregions = Region::create('150')->subregions;
        $this->assertSame('Eastern Europe', $subregions[151]->name);
        $this->assertFalse(isset($subregions['057']));
    }

    public function testCountryCodes()
    {
        $this->assertEquals([
                'AX','DK','EE','FI','FO','GB','GG','IM','IE','IS','JE','LT','LV','NO','SJ','SE','AL','AD','BA','ES',
                'GI','GR','HR','IT','MK','MT','ME','PT','SM','RS','SI','VA','XK','AT','BE','CH','DE','FR','LI','LU',
                'MC','NL','BG','BY','CZ','HU','MD','PL','RO','RU','SK','UA'],
            Region::create('150')->countryCodes
        );
    }

    public function testCountryNames()
    {
        $this->assertArraySubset(['RU' => 'Russia', 'DE' => 'Germany'], Region::create('150')->countryNames);
    }

    public function testCountries()
    {
        $countries = Region::create('150')->countries;
        $this->assertSame('Russia', $countries['RU']->name);
        $this->assertSame('Germany', $countries['DE']->name);
        $this->assertFalse(isset($countries['US']));
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\Region', Region::intldataClassName());
    }
}