<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

use yii\base\BaseObject;

/**
 * Class Timezone
 * @package tigrov\country
 *
 * @property string $code timezone code
 * @property string $name timezone name
 * @method static string[] codes(string $countryCode = null) Returns time zone names from IANA tame zone database
 * @method static array names(string[] $codes = null) Returns list of timezone names
 * @method static string intlName(\IntlTimeZone $intlTimeZone) Generate timezone name from Intl data
 * @method static array countriesTimezoneCode() Returns default timezone code for each country
 * @method static string countryTimezoneCode(string $countryCode) Returns default timezone code of a country
 */
class Timezone extends BaseObject implements ModelInterface
{
    use IntldataTrait, CreateTrait, AllTrait;

    /**
     * @var string code of the timezone
     */
    public $code;

    /**
     * @return string
     */
    public function getName()
    {
        return static::name($this->code);
    }
}