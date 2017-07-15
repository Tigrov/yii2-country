<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

/**
 * This is the model class for table "city_translation".
 *
 * @property integer $geoname_id
 * @property string $language_code
 * @property string $value
 */
class CityTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['geoname_id', 'language_code', 'value'], 'required'],
            [['geoname_id'], 'integer'],
            [['value'], 'string'],
            [['language_code'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'geoname_id' => 'GeonameID',
            'language_code' => 'Language',
            'value' => 'Value',
        ];
    }
}
