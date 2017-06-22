<?php
/**
 * @link https://github.com/tigrov/yii2-country
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */

namespace tigrov\country;

/**
 * Bootstrap class
 *
 * @author Sergei Tigrov <rrr-r@ya.ru>
 */
class Bootstrap implements \yii\base\BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $app->controllerMap['migrate']['migrationNamespaces'][] = 'tigrov\country\migrations';
        }
    }
}