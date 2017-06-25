<?php

namespace tigrov\country;

use yii\db\Expression;

/**
 * This is the model class for table "region".
 *
 * @property integer $geoname_id
 * @property string $country_code
 * @property string $division_code
 * @property string $language_codes
 * @property string $name_en
 * @property string $timezone_code
 *
 * @property string $name local name of the region
 * @property Language[] $languages
 * @property City[] $cities
 * @property Country $country
 * @property Timezone $timezone
 * @property string $timezoneName
 */
class Division extends \yii\db\ActiveRecord implements ModelInterface
{
    use CreateActiveRecordTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'division';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code', 'name_en'], 'required'],
            [['geoname_id'], 'integer'],
            [['country_code'], 'in', 'range' => Country::codes()],
            [['division_code'], 'string', 'max' => 3],
            [['language_codes'], 'string', 'max' => 255],
            [['name_en'], 'string', 'max' => 100],
            [['timezone_code'], 'in', 'range' => Timezone::codes()],
            [['latitude', 'longitude'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function all()
    {
        return static::find()->with('translation')->all();
    }

    /**
     * Get list of division codes by a country code.
     *
     * @param string $countryCode the country code
     * @return array list of division codes of the country
     */
    public static function codes($countryCode)
    {
        return static::find()->select(['division_code'])->where(['country_code' => $countryCode])->column();
    }

    /**
     * Get list of division names by a country code.
     *
     * @param string $countryCode the country code
     * @return array list of division names of the country
     */
    public static function names($countryCode)
    {
        return static::find()
            ->select([new Expression('COALESCE(t.value, d.name_en) name'), 'd.division_code'])
            ->alias('d')
            ->joinWith(['translation t'])
            ->where(['country_code' => $countryCode])
            ->orderBy('name')
            ->indexBy('division_code')
            ->column();
    }

    /**
     * Get the division code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->division_code;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->translation ? $this->translation->value : $this->name_en;
    }

    /**
     * Get list of language codes for the division
     *
     * @return string[]
     */
    public function getLanguageCodes()
    {
        return $this->language_codes ? explode(',', $this->language_codes) : [];
    }

    /**
     * Get list of languages for the division.
     *
     * @return Language[]
     */
    public function getLanguages()
    {
        $list = [];
        foreach ($this->languageCodes as $languageCode) {
            $list[$languageCode] = Language::create($languageCode);
        }

        return $list;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::class, ['country_code' => 'country_code', 'division_code' => 'division_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['code' => 'country_code']);
    }

    /**
     * @return Timezone
     */
    public function getTimezone()
    {
        return Timezone::create($this->timezone_code);
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
    public function getTranslations()
    {
        return $this->hasMany(DivisionTranslation::class, ['country_code' => 'country_code', 'division_code' => 'division_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation()
    {
        return $this->hasOne(DivisionTranslation::class, ['country_code' => 'country_code', 'division_code' => 'division_code'])
            ->andOnCondition(['language_code' => Locale::languageCode(\Yii::$app->language)]);
    }
}
