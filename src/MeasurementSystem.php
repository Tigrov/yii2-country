<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

use yii\base\Object;

/**
 * Class MeasurementSystem
 * @package tigrov\country
 *
 * @method static string countryMeasurementSystemCode(string $countryCode) Returns measurement system code for a country
 */
class MeasurementSystem extends Object implements ModelInterface
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