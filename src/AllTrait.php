<?php

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