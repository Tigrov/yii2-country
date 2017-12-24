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
        return 'division_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code', 'division_code', 'language_code', 'value'], 'required'],
            [['value'], 'string'],
            [['country_code'], 'in', 'range' => Country::codes()],
            [['division_code'], 'string', 'max' => 3],
            [['language_code'], 'string', 'max' => 25],
            [['country_code', 'division_code'], 'exist', 'skipOnError' => true, 'targetClass' => Division::className(), 'targetAttribute' => ['country_code' => 'country_code', 'division_code' => 'division_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_code' => 'Country',
            'division_code' => 'Division',
            'language_code' => 'Language',
            'value' => 'Value',
        ];
    }
}
