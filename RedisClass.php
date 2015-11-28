<?php
namespace Yii2cms\lib;

use Yii;
use yii\web\NotFoundHttpException;
use \Redis;

class RedisClass
{
     private static $redisFlag;
     private static $redisConf;
     public static function setConfig ($config)
     {
        self::$redisConf    = $config;
     }
     
     public static function getInstance($flag)
     {
		if (!isset(\Yii::$app->params['redis'])) {
			throw new NotFoundHttpException('Yii:$app->params[\'redis\'] not found.');
			return ;	
		}
        if (isset(self::$redisFlag[$flag]) && !empty(self::$redisFlag[$flag])) {
            return self::$redisFlag[$flag];
        } else {
            $redis      = self::$redisFlag[$flag] = new Redis();
            $redisHost  = self::$redisConf[$flag]['host'];
            $redisPort  = self::$redisConf[$flag]['port'];
            $delay      = 150;
            try {
                $redis->connect($redisHost, $redisPort, $delay);
            } catch (Exception $e) {
                $redis->connect($redisHost, $redisPort, $delay);
            }
            return $redis;
        }
     }
}
