<?php

namespace tigrov\country;

use yii\base\Object;

class Continent extends Object implements ModelInterface
{
    use IntldataTrait, CreateTrait, AllTrait;

    /**
     * @var string code of the continent
     */
    public $code;

    /**
     * @var array list of continent's countries
     */
    private $_countries;

    /**
     * @return string
     */
    public function getName()
    {
        return static::name($this->code);
    }

    /**
     * List of continent's country codes.
     *
     * @return string[] country codes of the continent
     */
    public function getCountryCodes()
    {
        return static::countryCodes($this->code);
    }

    /**
     * List of continent's country names.
     *
     * @return array country names of the continent
     */
    public function getCountryNames()
    {
        return array_intersect_key(Country::names(), array_flip($this->countryCodes));
    }

    /**
     * List of continent's countries.
     *
     * @return Country[] countries of the continent
     */
    public function getCountries()
    {
        if ($this->_countries === null) {
            $this->_countries = [];
            foreach ($this->countryCodes as $countryCode) {
                $this->_countries[$countryCode] = Country::create($countryCode);
            }
        }

        return $this->_countries;
    }
}