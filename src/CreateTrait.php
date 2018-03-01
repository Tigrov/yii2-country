<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

trait CreateTrait
{
    /**
     * Create a model for the class
     *
     * @param string $code code to create the model
     * @return static the created model
     */
    public static function create($code)
    {
        return \Yii::createObject(['class' => static::class, 'code' => $code]);
    }
}