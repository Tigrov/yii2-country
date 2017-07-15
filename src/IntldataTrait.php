<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

use yii\base\UnknownMethodException;

/**
 * Trait IntldataTrait is a proxy to \tigrov\intldata static classes
 *
 * @method static string[] codes() Returns list of codes
 * @method static bool has(string $code) Returns a boolean indicating whether data has a code
 * @method static array names(string[] $codes = null) Returns list of names with code keys [code => name]
 * @method static string name(string $code) Returns name by code
 */
trait IntldataTrait
{
    /**
     * @inheritdoc
     */
    public function __call($name, $arguments)
    {
        return static::__callStatic($name, $arguments);
    }

    /**
     * @inheritdoc
     */
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