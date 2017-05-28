<?php

namespace tigrov\country;


trait CreateActiveRecordTrait
{
    /**
     * Create a model for the class
     *
     * @param string $code primary key to create the model
     * @return static the created model
     */
    public static function create($code)
    {
        return static::findOne($code);
    }
}