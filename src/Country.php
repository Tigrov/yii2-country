<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

/**
 * This is the model class for table "country".
 *
 * @property string $code
 * @property integer $geoname_id
 * @property integer $capital_geoname_id
 * @property string $language_code
 * @property string $currency_code
 * @property string $timezone_code
 * @property string $latitude
 * @property string $longitude
 * @property string $name_en
 *
 * @property string $name
 * @property string $continentCode
 * @property string $continentName
 * @property string $regionCode
 * @property string $regionName
 * @property string $subregionCode
 * @property string $subregionName
 * @property string $measurementSystemCode
 * @property string $measurementSystemName
 * @property string $localeCode
 * @property string $localeName
 * @property string $languageCode
 * @property string $languageName
 * @property string $currencyCode
 * @property string $currencyName
 * @property string $timezoneCode
 * @property string $timezoneName
 * @property string[] $localeCodes
 * @property string[] $localeNames
 * @property string[] $languageCodes
 * @property string[] $languageNames
 *
 * @property Division[] $divisions
 * @property City[] $cities
 *
 * @property City $capital
 * @property Continent $continent
 * @property Region $region
 * @property Subregion $subregion
 * @property MeasurementSystem $measurementSystem
 * @property Language $language
 * @property Locale $locale
 * @property Currency $currency
 * @property Timezone $timezone
 * @property Locale[] $locales
 * @property Language[] $languages
 * @property \Rinvex\Country\Country $rinvex
 */
class Country extends \yii\db\ActiveRecord implements ModelInterface
{
    use IntldataTrait, CreateActiveRecordTrait;

    /**
     * @var \Rinvex\Country\Country
     */
    private $_rinvex;

    /**
     * @var Locale[] list of locales
     */
    private $_locales;

    /**
     * @var Language[] list of languages
     */
    private $_languages;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'geoname_id', 'capital_geoname_id', 'language_code', 'currency_code', 'timezone_code', 'latitude', 'longitude', 'name_en'], 'required'],
            [['geoname_id', 'capital_geoname_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['code'], 'in', 'range' => static::codes()],
            [['language_code'], 'string', 'max' => 3],
            [['currency_code'], 'in', 'range' => Currency::codes()],
            [['timezone_code'], 'in', 'range' => function ($model, $attribute) { return Timezone::codes($model->code); }],
            [['name_en'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'geoname_id' => 'Geoname ID',
            'capital_geoname_id' => 'Capital ID',
            'language_code' => 'Language',
            'currency_code' => 'Currency',
            'timezone_code' => 'Timezone',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'name_en' => 'Name',
        ];
    }

    /**
     * @return \Rinvex\Country\Country
     */
    public function getRinvex()
    {
        if ($this->_rinvex === null) {
            $this->_rinvex = \Rinvex\Country\CountryLoader::country($this->code);
        }

        return $this->_rinvex;
    }

    /**
     * Objects' classes
     * @return array
     */
    protected static function objectsClasses() {
        return [
            'continent' => Continent::className(),
            'region' => Region::className(),
            'subregion' => Subregion::className(),
            'measurementsystem' => MeasurementSystem::className(),
            'language' => Language::className(),
            'locale' => Locale::className(),
            'currency' => Currency::className(),
            'timezone' => Timezone::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        $name = strtolower($name);
        $objectName = $name;
        $classMethod = 'create';
        if (0 === substr_compare($name, 'name', -4, 4, true)) {
            $objectName = substr($name, 0, -4);
            $classMethod = 'name';
        }
        $objectsClasses = static::objectsClasses();
        if (isset($objectsClasses[$objectName])) {
            $codeGetter = 'get' . $objectName . 'Code';
            $className = $objectsClasses[$objectName];

            return $className::$classMethod($this->$codeGetter());
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public static function all()
    {
        return static::find()->indexBy('code')->all();
    }

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
    public function getContinentCode()
    {
        return Continent::countryContinentCode($this->code);
    }

    /**
     * @return string
     */
    public function getRegionCode()
    {
        return Region::countryRegionCode($this->code);
    }

    /**
     * @return string
     */
    public function getSubregionCode()
    {
        return Subregion::countrySubregionCode($this->code);
    }

    /**
     * Get measurement system code
     *
     * @return string measurement system code 'US' or 'SI'
     */
    public function getMeasurementSystemCode()
    {
        return MeasurementSystem::countryMeasurementSystemCode($this->code);
    }

    /**
     * @return string[] list of locale codes
     */
    public function getLocaleCodes()
    {
        return Locale::countryLocaleCodes($this->code);
    }

    /**
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

    /**
     * @return string[] list of language codes
     */
    public function getLanguageCodes()
    {
        $list = [];
        foreach ($this->localeCodes as $locale) {
            $languageCode = Locale::languageCode($locale);
            $list[$languageCode] = $languageCode;
        }

        return $list;
    }

    /**
     * @return string[] list of language names
     */
    public function getLanguageNames()
    {
        $list = [];
        foreach ($this->getLanguageCodes() as $languageCode) {
            $list[$languageCode] = Language::name($languageCode);
        }

        return $list;
    }

    /**
     * @return Language[] list of languages
     */
    public function getLanguages()
    {
        if ($this->_languages === null) {
            $this->_languages = [];
            foreach ($this->getLanguageCodes() as $languageCode) {
                $this->_languages[$languageCode] = Language::create($languageCode);
            }
        }

        return $this->_languages;
    }

    /**
     * @return string
     */
    public function getLocaleCode()
    {
        return Locale::languageCountryLocaleCode($this->language_code, $this->code);
    }

    /**
     * @return string
     */
    public function getLanguageCode()
    {
        return $this->language_code;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * @return string
     */
    public function getTimezoneCode()
    {
        return $this->timezone_code;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivisions()
    {
        return $this->hasMany(Division::className(), ['country_code' => 'code'])->indexBy('division_code');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['country_code' => 'code'])->indexBy('geoname_id');
    }

    /**
     * @return City
     */
    public function getCapital()
    {
        return City::create($this->capital_geoname_id);
    }
}