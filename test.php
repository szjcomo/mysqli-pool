<?php
/**
 * |-----------------------------------------------------------------------------------
 * @Copyright (c) 2014-2018, http://www.sizhijie.com. All Rights Reserved.
 * @Website: www.sizhijie.com
 * @Version: 思智捷信息科技有限公司
 * @Author : szjcomo 
 * |-----------------------------------------------------------------------------------
 */

require './vendor/autoload.php';

use EasySwoole\Mysqli\Config;
use EasySwoole\MysqliPool;

$config = new \szjcomo\mysqli\Config([
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'xxx',
    'password' => 'xxx',
    'database' => 'xxx',
    'prefix'=>'xxx',
    'debug'	=>true
]);

\szjcomo\mysqliPool\Mysql::getInstance()->register('default',$config);

go(function(){
	$db = \szjcomo\mysqliPool\Mysql::defer('default');
	$list = $db->name('admin_user')->select();
	print_r($list);
});