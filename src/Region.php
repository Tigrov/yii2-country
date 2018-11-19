<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

use yii\base\BaseObject;

/**
 * Class Region
 * @package tigrov\country
 *
 * @property string $code UN region code
 * @property string $name region name
 * @property string[] $subregionCodes list of region's UN subregion codes
 * @property array $subregionNames list of region's subregion names
 * @property Subregion[] $subregions list of region's subregions
 * @property string[] $countryCodes list of continent's ISO 3166-1 alpha-2 country codes
 * @property array $countryNames list of continent's country names
 * @property Country[] $countries list of continent's countries
 * @method static array countryCodes(string $regionCode = null) Returns list of ISO 3166-1 alpha-2 country codes for a region
 * @method static string countryRegionCode(string $countryCode) Returns UN region code for a country
 */
class Region extends BaseObject implements ModelInterface
{
    use IntldataTrait, CreateTrait, AllTrait;

    /**
     * @var string code of the region
     */
    public $code;

    /**
     * @var Subregion[] list of subregions
     */
    private $_subregions;

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

    /**
     * Get list of subregion codes by a region
     *
     * @return string[] list of subregion codes
     */
    public function getSubregionCodes()
    {
        return Subregion::codes($this->code);
    }

    /**
     * Get list of subregion names
     *
     * @return string[] list of subregion names
     */
    public function getSubregionNames()
    {
        return Subregion::names($this->code);
    }

    /**
     * Get list of subregions
     *
     * @return Subregion[] list of subregions
     */
    public function getSubregions()
    {
        if ($this->_subregions === null) {
            $this->_subregions = [];
            foreach ($this->getSubregionCodes() as $subregionCode) {
                $this->_subregions[$subregionCode] = Subregion::create($subregionCode);
            }
        }

        return $this->_subregions;
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