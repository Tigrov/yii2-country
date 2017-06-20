<?php

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