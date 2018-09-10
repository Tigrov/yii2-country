<?php

namespace tigrov\tests\unit\country\migrations;

class m170405_112954_init extends \tigrov\country\migrations\m170405_112954_init
{
    const DATA_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
    const COUNTRY_CSV = self::DATA_DIR . 'country.csv';
    const DIVISION_CSV = self::DATA_DIR . 'division.csv';
    const DIVISION_TRANSLATION_CSV = self::DATA_DIR . 'division_translation.csv';
    const CITY_CSV = self::DATA_DIR . 'city.csv';
    const CITY_TRANSLATION_CSV = self::DATA_DIR . 'city_translation.csv';
}