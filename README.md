## yii2-cmslib


###``2015/12/02 add file Redis.php``
>usage �� echo <<< EOT

    use \Yii2cms\lib\RedisClass;

    $redis=RedisClass::getInstance();
    //$redis=RedisClass::getInstance('cms_slave');
    //$redis=RedisClass::getInstance('msgque_slave');

    var_dump($redis);
    var_dump($redis->get($key));
> EOT

<br>
#### ``[notes]``
Redis��ʽ���� ``(\Yii::$app->params['redis'])`` ���£�

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


over
