<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

use yii\base\Object;

/**
 * Class Language
 * @package tigrov\country
 *
 * @method static string languageName(string $code) Returns name of a language in the language
 * @method static array languageNames(string[] $codes = null, bool $sort = true) Returns list of language names in the each language
 * @method static string|null findMainCode(string[] $codes) Find main ISO 639-1 language code in a list
 * @method static string[] countryLanguageCodes(string $countryCode) Returns list of country ISO 639-1 language codes
 * @method static array countriesLanguageCode() Returns default lISO 639-1 anguage code for each country
 * @method static string countryLanguageCode(string $countryCode) Returns default ISO 639-1 language code of a country
 */
class Language extends Object implements ModelInterface
{
    use IntldataTrait, CreateTrait, AllTrait;

    /**
     * @var string code of the language
     */
    public $code;

    /**
     * @var Locale[] list of locales
     */
    private $_locales;

    /**
     * @return string
     */
    public function getName()
    {
        return static::name($this->code);
    }

    /**
     * Get list of locale codes
     *
     * @return string[] list of locale codes
     */
    public function getLocaleCodes()
    {
        return Locale::languageLocaleCodes($this->code);
    }

    /**
     * Get list of locale names
     *
     * @return string[] list of locale names
     */
    public function getLocaleNames()
    {
        $list = [];
        foreach ($this->getLocaleCodes() as $localeCode) {
            $list[$localeCode] = Locale::name($localeCode);
        }

        return $list;
    }

    /**
     * Get list of locales
     *
     * @return Locale[] list of locales
     */
    public function getLocales()
    {
        if ($this->_locales === null) {
            $this->_locales = [];
            foreach ($this->getLocaleCodes() as $localeCode) {
                $this->_locales[$localeCode] = Locale::create($localeCode);
            }
        }

        return $this->_locales;
    }
}