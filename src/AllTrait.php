<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;


trait AllTrait
{
    /**
     * Get all models for the class
     *
     * @return static[] all models of the class
     */
    public static function all()
    {
        $list = [];
        foreach (static::codes() as $code) {
            $list[$code] = static::create($code);
        }

        return $list;
    }
}