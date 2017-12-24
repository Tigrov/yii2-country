<?php

namespace tigrov\tests\unit\country;

use tigrov\country\Locale;

class LocaleTest extends TestCase
{
    public function testCode()
    {
        $this->assertSame('en_US', Locale::create('en_US')->code);
    }

    public function testName()
    {
        $this->assertSame('Russian (Russia)', Locale::create('ru_RU')->name);
        $this->assertSame('English (United States)', Locale::create('en_US')->name);
    }

    public function testLanguageCode()
    {
        $this->assertSame('en', Locale::create('en_US')->languageCode);
    }

    public function testLanguageName()
    {
        $this->assertSame('English', Locale::create('en_US')->languageName);
    }

    public function testLanguage()
    {
        $this->assertSame('English', Locale::create('en_US')->language->name);
    }

    public function testIntldataClassName()
    {
        $this->assertSame('\\tigrov\\intldata\\Locale', Locale::intldataClassName());
    }
}