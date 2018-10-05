<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

/**
 * This is the model class for table "division_translation".
 *
 * @property string $country_code
 * @property string $division_code
 * @property string $language_code
 * @property string $value
 */
class DivisionTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%division_translation}}';
    }
}
