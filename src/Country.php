<?php

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
 */
class Country extends \yii\db\ActiveRecord implements ModelInterface
{
    use IntldataTrait, CreateActiveRecordTrait;

    /**
     * Objects' classes
     */
    const OBJECT_CLASSES = [
        'continent' => Continent::class,
        'region' => Region::class,
        'subregion' => Subregion::class,
        'measurementsystem' => MeasurementSystem::class,
        'language' => Language::class,
        'locale' => Locale::class,
        'currency' => Currency::class,
        'timezone' => Timezone::class,
        'capital' => City::class,
    ];

    /**
     * @var \Rinvex\Country\Country
     */
    private $_rinvex;

    /**
     * @var array list of objects
     */
    private $_objects = [];

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
            [['timezone_code'], 'in', 'range' => function($model, $attribute) { return Timezone::codes($model->code); }],
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
     * @inheritdoc
     */
    public function __get($name)
    {
        $name = strtolower($name);
        if (isset(static::OBJECT_CLASSES[$name])) {
            if (!isset($this->_objects[$name])) {
                $codeGetter = 'get' . $name . 'Code';
                $className = static::OBJECT_CLASSES[$name];
                $this->_objects[$name] = $className::create($this->$codeGetter());
            }

            return $this->_objects[$name];
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
    public function getContinentName()
    {
        return Continent::name($this->getContinentCode());
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
    public function getRegionName()
    {
        return Region::name($this->getRegionCode());
    }

    /**
     * @return string
     */
    public function getSubregionCode()
    {
        return Region::countrySubregionCode($this->code);
    }

    /**
     * @return string
     */
    public function getSubregionName()
    {
        return Region::name($this->getSubregionCode());
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
     * Get measurement system name
     *
     * @return string measurement system name
     */
    public function getMeasurementSystemName()
    {
        return MeasurementSystem::name($this->getMeasurementSystemCode());
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
    public function getLocaleName()
    {
        return Locale::name($this->getLocaleCode());
    }

    /**
     * @return string
     */
    public function getLanguageName()
    {
        return Language::name($this->language_code);
    }

    /**
     * @return string
     */
    public function getCurrencyName()
    {
        return Currency::name($this->currency_code);
    }

    /**
     * @return string
     */
    public function getTimezoneName()
    {
        return Timezone::name($this->timezone_code);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivisions()
    {
        return $this->hasMany(Division::class, ['country_code' => 'code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::class, ['country_code' => 'code']);
    }

    /**
     * @return \yii\db\BaseActiveRecord
     */
    public function getCapital()
    {
        return City::findOne($this->capital_geoname_id);
    }
}