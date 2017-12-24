<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Timezone;

class TimezoneTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('America/New_York', Timezone::create('America/New_York')->code);
    }

    public function testName()
    {
        $this->assertSame('(EST) New York Time', Timezone::create('America/New_York')->name);
        $this->assertSame('(GMT+8) China Time', Timezone::create('Asia/Shanghai')->name);
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\Timezone', Timezone::intldataClassName());
    }
}