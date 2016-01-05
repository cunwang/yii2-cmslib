<?php
/**
 * file Redis.php
 * ------------------------------------------------------ +
 * @abstract 用于Yii2 多Redis的情况。
 *  - 单例
 *  - 方便切换，没有设置$flag时默认选择第一个。
 * 
 * @author wangcun
 * @date 2015/12/02
 * @useage 

   echo <<< EOT
		use \Yii2cms\lib\RedisClass; 

		$redis=RedisClass::getInstance();
		//$redis=RedisClass::getInstance('cms_slave'); 
		//$redis=RedisClass::getInstance('msgque_slave'); 

		var_dump($redis);
		var_dump($redis->get($key));
   EOT
 * ------------------------------------------------------ +
*/

namespace Yii2cms\lib;

use Yii;
use \Redis;
use yii\web\NotFoundHttpException;

/**
 * [notes] 
 * redis配置文件 Yii::$app->params['redis'] 格式如下:
	$redisCon   = [
		'msgque_master' => [
			'host'  => '10.15.209.110',
			'port'  => '6379'
		],
		'msgque_slave'  => [
			'host'  => '10.15.209.119',
			'port'  => '6379'   
		],
		....
	];
 */

class RedisClass
{

     private static $redisFlag;
     private static $redisConf;
	 private static $delay	= 150;

     static public function setConfig($config)
     {
        self::$redisConf    = $config;
     }
     
     static public  function getInstance($flag = null)
     {
		if (!isset(\Yii::$app->params['redis'])) {
			throw new NotFoundHttpException('Yii:$app->params[\'redis\'] not found.');
			return ;	
		}

		if (! is_array(\Yii::$app->params['redis'])) {
			throw new NotFoundHttpException('Yii:$app->params[\'redis\'] must be Array.');
			return ;	
		}

		self::$redisConf=\Yii::$app->params['redis'];

		if (empty($flag)) {
			$redislist	= array_keys(self::$redisConf);
			reset($redislist);
			$flag		= current($redislist);
		}

		if (! array_key_exists($flag, self::$redisConf)) {
			throw new NotFoundHttpException('Yii:$app->params[\'redis\']['.$flag.'] .');
			return ;
		}

        if (isset(self::$redisFlag[$flag]) && self::$redisFlag[$flag] != null) {
            return self::$redisFlag[$flag];
        } else {
            $redis      = self::$redisFlag[$flag] = new Redis();
            $redisHost  = self::$redisConf[$flag]['host'];
            $redisPort  = self::$redisConf[$flag]['port'];

			if (isset(self::$redisConf[$flag]['delay'])) {
				$delay  = self::$redisConf[$flag]['delay'];
            	$delay  = (empty($delay) || intval($delay) < 1) ? self::$delay : $delay;
			} else {
				$delay	= self::$delay;
			}

			#if failed will try again
            try {
                $redis->connect($redisHost, $redisPort, $delay);
            } catch (RedisException $e) {
                $redis->connect($redisHost, $redisPort, $delay);
            }
			
			self::$redisFlag[$flag]	= $redis;
            return $redis;
        }
     }
}
