<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Currency;

class CurrencyTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('USD', Currency::create('USD')->code);
    }

    public function testName()
    {
        $this->assertSame('Russian Ruble', Currency::create('RUB')->name);
        $this->assertSame('US Dollar', Currency::create('USD')->name);
        $this->assertSame('Chinese Yuan', Currency::create('CNY')->name);
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\Currency', Currency::intldataClassName());
    }
}