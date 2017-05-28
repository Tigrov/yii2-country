<?php

namespace tigrov\country;

use yii\base\Object;

class Locale extends Object implements ModelInterface
{
    use IntldataTrait, CreateTrait, AllTrait;

    /**
     * @var string code of the locale
     */
    public $code;

    /**
     * @return string
     */
    public function getName()
    {
        return static::name($this->code);
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return static::languageCode($this->code);
    }

    /**
     * @return string
     */
    public function getLanguageName()
    {
        return Language::name($this->getLanguageCode());
    }

    /**
     * @return Language
     */
    public function getLanguage()
    {
        return Language::create($this->getLanguageCode());
    }
}