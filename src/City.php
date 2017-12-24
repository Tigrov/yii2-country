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
 * @property Division $division
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
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['geoname_id', 'country_code', 'name_en'], 'required'],
            [['geoname_id'], 'integer'],
            [['country_code'], 'in', 'range' => Country::codes()],
            [['division_code'], 'string', 'max' => 3],
            [['name_en'], 'string', 'max' => 200],
            [['timezone_code'], 'in', 'range' => Timezone::codes()],
            [['latitude', 'longitude'], 'number'],
            [['country_code', 'division_code'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['country_code' => 'country_code', 'division_code' => 'division_code']],
        ];
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
    public function getDivision()
    {
        return $this->hasOne(Division::className(), ['country_code' => 'country_code', 'division_code' => 'division_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['code' => 'country_code']);
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
        return $this->hasMany(CityTranslation::className(), ['geoname_id' => 'geoname_id'])
            ->indexBy('language_code');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslation()
    {
        return $this->hasOne(CityTranslation::className(), ['geoname_id' => 'geoname_id'])
            ->andOnCondition(['language_code' => Locale::languageCode(\Yii::$app->language)]);
    }
}
