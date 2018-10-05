<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

use yii\db\Expression;

/**
 * This is the model class for table "city".
 *
 * @property integer $geoname_id
 * @property string $country_code
 * @property string $division_code
 * @property string $name_en
 * @property string $timezone_code
 *
 * @property Country $country
 * @property string $countryName
 * @property Division $division
 * @property string $divisionName
 * @property Timezone $timezone
 * @property string $timezoneName
 */
class City extends \yii\db\ActiveRecord implements ModelInterface
{
    use CreateActiveRecordTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }

    /**
     * @inheritdoc
     */
    public static function all()
    {
        return static::find()->with('translation')->indexBy('geoname_id')->all();
    }

    /**
     * Get list of city geoname ids by country and division codes.
     *
     * @param string $countryCode country code
     * @param string $divisionCode division code
     * @return array list of city geoname ids of country and division
     */
    public static function codes($countryCode, $divisionCode = null)
    {
        $query = static::find()->select(['geoname_id'])->where(['country_code' => $countryCode]);
        if ($divisionCode !== null) {
            $query->andWhere(['division_code' => $divisionCode]);
        }

        return $query->column();
    }

    /**
     * Get list of city names by country and division codes.
     *
     * @param string $countryCode country code
     * @param string $divisionCode division code
     * @return array list of city names of country and division
     */
    public static function names($countryCode, $divisionCode = null)
    {
        $where = ['country_code' => $countryCode];
        if ($divisionCode !== null) {
            $where['division_code'] = $divisionCode;
        }

        return static::find()
            ->select([new Expression('COALESCE(t.value, c.name_en) AS name'), 'c.geoname_id'])
            ->alias('c')
            ->joinWith(['translation t'])
            ->where($where)
            ->orderBy('name')
            ->indexBy('geoname_id')
            ->column();
    }

    public function getCode()
    {
        return $this->geoname_id;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->translation ? $this->translation->value : $this->name_en;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['code' => 'country_code']);
    }

    /**
     * @return string
     */
    public function getCountryName()
    {
        return $this->country->name;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDivision()
    {
        return $this->hasOne(Division::class, ['country_code' => 'country_code', 'division_code' => 'division_code']);
    }

    /**
     * @return string
     */
    public function getDivisionName()
    {
        return $this->division ? $this->division->name : null;
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
        return $this->hasMany(CityTranslation::class, ['geoname_id' => 'geoname_id'])
            ->indexBy('language_code');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation()
    {
        return $this->hasOne(CityTranslation::class, ['geoname_id' => 'geoname_id'])
            ->andOnCondition(['language_code' => Locale::languageCode(\Yii::$app->language)]);
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        $fields = ['code', 'name', 'divisionName', 'countryName', 'timezoneName'];
        return array_merge(parent::extraFields(), array_combine($fields, $fields));
    }
}
