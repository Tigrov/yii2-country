<?php

namespace tigrov\tests\unit\country;

use tigrov\country\MeasurementSystem;

class MeasurementSystemTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('SI', MeasurementSystem::create('SI')->code);
    }

    public function testName()
    {
        $this->assertSame('International System (metre, kilogram)', MeasurementSystem::create('SI')->name);
        $this->assertSame('United States (inch, pound)', MeasurementSystem::create('US')->name);
    }

    public function testAll()
    {
        $measurementSystems = MeasurementSystem::all();
        $this->assertSame('International System (metre, kilogram)', $measurementSystems['SI']->name);
        $this->assertSame('United States (inch, pound)', $measurementSystems['US']->name);
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\MeasurementSystem', MeasurementSystem::intldataClassName());
    }
}