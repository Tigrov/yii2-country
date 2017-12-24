<?php

namespace tigrov\tests\unit\country;

use tigrov\country\City;

class CityTest extends TestCase
{
    public function testCodes()
    {
        // Moscow, Yakutsk
        $this->assertEmpty(array_diff([524901, 2013159], City::codes('RU')));

        // New York, Los Angeles
        $this->assertEmpty(array_diff([5128581, 5368361], City::codes('US')));

        // New York, Los Angeles
        $this->assertSame([5128581, 5368361], array_diff([5128581, 5368361], City::codes('RU')));

        // New York, Los Angeles in California
        $this->assertSame([5128581], array_diff([5128581, 5368361], City::codes('US', 'CA')));
    }

    public function testNames()
    {
        // Moscow, Yakutsk
        $this->assertArraySubset([524901 => 'Moscow', 2013159 => 'Yakutsk'], City::names('RU'));

        // New York, Los Angeles
        $this->assertArraySubset([5128581 => 'New York', 5368361 => 'Los Angeles'], City::names('US'));

        // New York, Los Angeles
        $this->assertSame([5128581 => 'New York', 5368361 => 'Los Angeles'], array_diff_assoc([5128581 => 'New York', 5368361 => 'Los Angeles'], City::names('RU')));

        // New York, Los Angeles in California
        $this->assertSame([5128581 => 'New York'], array_diff_assoc([5128581 => 'New York', 5368361 => 'Los Angeles'], City::names('US', 'CA')));
    }

    public function testCode()
    {
        $this->assertSame(524901, City::create(524901)->code);
    }

    public function testName()
    {
        $this->assertSame('Moscow', City::create(524901)->name);
        $this->assertSame('Los Angeles', City::create(5368361)->name);
    }

    public function testAll()
    {
        $cities = City::all();
        $this->assertSame('Moscow', $cities[524901]->name);
        $this->assertSame('Los Angeles', $cities[5368361]->name);
    }

    public function testDivision()
    {
        $this->assertSame('CA', City::create(5368361)->division_code);
        $this->assertSame('CA', City::create(5368361)->division->code);
    }

    public function testCountry()
    {
        $this->assertSame('US', City::create(5368361)->country_code);
        $this->assertSame('US', City::create(5368361)->country->code);
    }

    public function testTimezone()
    {
        $this->assertSame('America/Los_Angeles', City::create(5368361)->timezone_code);
        $this->assertSame('America/Los_Angeles', City::create(5368361)->timezone->code);
    }

    public function testTimezoneName()
    {
        $this->assertSame('(PST) Los Angeles Time', City::create(5368361)->timezoneName);
    }
}