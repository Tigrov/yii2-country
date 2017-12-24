<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Division;

class DivisionTest extends TestCase
{
    public function testCodes()
    {
        $this->assertEmpty(array_diff(['MO', 'SA'], Division::codes('RU')));
        $this->assertEmpty(array_diff(['NY', 'CA'], Division::codes('US')));
        $this->assertSame(['XXX'], array_diff(['XXX', 'NY', 'CA'], Division::codes('US')));
    }

    public function testNames()
    {
        $this->assertArraySubset(['NY' => 'New York', 'CA' => 'California'], Division::names('US'));
    }

    public function testCode()
    {
        $this->assertSame('CA', Division::create(['country_code' => 'US', 'division_code' => 'CA'])->code);
    }

    public function testName()
    {
        $this->assertSame('California', Division::create(['country_code' => 'US', 'division_code' => 'CA'])->name);
        $this->assertSame('New York', Division::create(['country_code' => 'US', 'division_code' => 'NY'])->name);
    }

    public function testLanguageCodes()
    {
        $languageCodes = Division::create(['country_code' => 'US', 'division_code' => 'NM'])->languageCodes;
        $this->assertTrue(in_array('es', $languageCodes));
        $this->assertFalse(in_array('ru', $languageCodes));
    }

    public function testLanguages()
    {
        $languages = Division::create(['country_code' => 'US', 'division_code' => 'NM'])->languages;
        $this->assertSame('Spanish', $languages['es']->name);
        $this->assertFalse(isset($languages['ru']));
    }

    public function testCities()
    {
        $cities = Division::create(['country_code' => 'US', 'division_code' => 'CA'])->cities;
        // Los Angeles in California
        $this->assertSame('Los Angeles', $cities[5368361]->name);
        // New York in California
        $this->assertFalse(isset($cities[5128581]));
    }

    public function testCountry()
    {
        $division = Division::create(['country_code' => 'US', 'division_code' => 'CA']);
        $this->assertSame('US', $division->country_code);
        $this->assertSame('US', $division->country->code);
    }

    public function testTimezone()
    {
        $division = Division::create(['country_code' => 'US', 'division_code' => 'CA']);
        $this->assertSame('America/Los_Angeles', $division->timezone_code);
        $this->assertSame('America/Los_Angeles', $division->timezone->code);
    }

    public function testTimezoneName()
    {
        $this->assertSame('(PST) Los Angeles Time', Division::create(['country_code' => 'US', 'division_code' => 'CA'])->timezoneName);
    }
}