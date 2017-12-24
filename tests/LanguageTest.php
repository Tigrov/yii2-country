<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Language;

class LanguageTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('en', Language::create('en')->code);
    }

    public function testName()
    {
        $this->assertSame('Russian', Language::create('ru')->name);
        $this->assertSame('English', Language::create('en')->name);
        $this->assertSame('Chinese', Language::create('zh')->name);
    }

    public function testLocaleCodes()
    {
        $localeCodes = Language::create('en')->localeCodes;
        $this->assertTrue(in_array('en_US', $localeCodes));
        $this->assertTrue(in_array('en_GB', $localeCodes));
        $this->assertFalse(in_array('ru_RU', $localeCodes));
    }

    public function testLocaleNames()
    {
        $localeNames = Language::create('en')->localeNames;
        $this->assertSame('English (United States)', $localeNames['en_US']);
        $this->assertSame('English (United Kingdom)', $localeNames['en_GB']);
        $this->assertFalse(isset($localeNames['ru_RU']));
    }

    public function testLocales()
    {
        $locales = Language::create('en')->locales;
        $this->assertSame('English (United States)', $locales['en_US']->name);
        $this->assertSame('English (United Kingdom)', $locales['en_GB']->name);
        $this->assertFalse(isset($locales['ru_RU']));
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\Language', Language::intldataClassName());
    }
}