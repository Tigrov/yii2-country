<?php

namespace tigrov\country;

use yii\base\Object;

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