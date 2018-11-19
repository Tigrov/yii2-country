<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

use yii\base\BaseObject;

/**
 * Class Subregion
 * @package tigrov\country
 *
 * @property string $code UN subregion code
 * @property string $name subregion name
 * @property string $egionCode subregion's UN region code
 * @property string $regionName subregion's region name
 * @property Region $regions subregion's region
 * @property string[] $countryCodes list of continent's ISO 3166-1 alpha-2 country codes
 * @property array $countryNames list of continent's country names
 * @property Country[] $countries list of continent's countries
 * @method static string[] codes(string $regionCode = null) Returns list of UN sub-region codes for a region
 * @method static array names(string $regionCode = null) String list of sub-region names for a region
 * @method static string regionCode(string $subregionCode) Returns UN region code by a subregion
 * @method static array countryCodes(string $subregionCode) Returns list of ISO 3166-1 alpha-2 country codes for a sub-region
 * @method static string countrySubregionCode(string $countryCode) Returns UN sub-region code for a country
 */
class Subregion extends BaseObject implements ModelInterface
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