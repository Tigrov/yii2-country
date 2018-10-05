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
        return '{{%city_translation}}';
    }
}
