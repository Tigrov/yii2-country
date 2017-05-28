<?php

namespace tigrov\country;

use yii\base\UnknownMethodException;

trait IntldataTrait
{
    public static function __callStatic($name, $arguments)
    {
        $className = static::intldataClassName();

        if (method_exists($className, $name)) {
            return forward_static_call_array([$className, $name], $arguments);
        } else {
            throw new UnknownMethodException('Unknown static method ' . static::class . '::' . $name);
        }
    }

    public static function intldataClassName()
    {
        return '\\tigrov\\intldata\\' . substr(strrchr(self::class, '\\'), 1);
    }
}