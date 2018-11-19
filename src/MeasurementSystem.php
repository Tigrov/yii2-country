<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

use yii\base\BaseObject;

/**
 * Class MeasurementSystem
 * @package tigrov\country
 *
 * @property string $code measurement system code
 * @property string $name measurement system name
 * @method static string countryMeasurementSystemCode(string $countryCode) Returns measurement system code for a country
 */
class MeasurementSystem extends BaseObject implements ModelInterface
{
    use IntldataTrait, CreateTrait, AllTrait;

    /**
     * @var string code of the measurement system
     */
    public $code;

    /**
     * @return string
     */
    public function getName()
    {
        return static::name($this->code);
    }
}