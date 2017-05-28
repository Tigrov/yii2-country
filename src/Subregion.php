<?php

namespace tigrov\country;

use yii\base\Object;

class Subregion extends Object implements ModelInterface
{
    use IntldataTrait, CreateTrait, AllTrait;

    /**
     * @var string code of the subregion
     */
    public $code;

    private $_region;

    /**
     * @var Country[] list of countries
     */
    private $_countries;

    /**
     * @return string
     */
    public function getName()
    {
        return static::name($this->code);
    }

    public function getRegionCode()
    {
        return static::regionCode($this->code);
    }

    public function getRegionName()
    {
        return Region::name($this->getRegionCode());
    }

    public function getRegion()
    {
        if ($this->_region === null) {
            $this->_region = Region::create($this->getRegionCode());
        }

        return $this->_region;
    }

    /**
     * Get list of country codes
     *
     * @return string[] list of country codes
     */
    public function getCountryCodes()
    {
        return static::countryCodes($this->code);
    }

    /**
     * Get list of country names
     *
     * @return string[] list of country names
     */
    public function getCountryNames()
    {
        $list = [];
        foreach ($this->getCountryCodes() as $code) {
            $list[$code] = Country::name($code);
        }

        return $list;
    }

    /**
     * Get list of countries
     *
     * @return Country[] list of countries
     */
    public function getCountries()
    {
        if ($this->_countries === null) {
            $this->_countries = [];
            foreach ($this->getCountryCodes() as $countryCode) {
                $this->_countries[$countryCode] = Country::create($countryCode);
            }
        }

        return $this->_countries;
    }
}