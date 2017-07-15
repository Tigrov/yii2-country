<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

trait CreateActiveRecordTrait
{
    /**
     * Create a model for the class
     *
     * @param mixed $code primary key value or a set of column values to create the model
     * @return static the created model
     */
    public static function create($code)
    {
        return static::findOne($code);
    }
}