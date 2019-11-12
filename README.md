# mysqli-pool
基于easyswoole官方的pool实现的mysql连接池
## 安装
```

composer require szjcomo/mysqli-pool

```
- 具体用户可参考thinkphp官方手册,在swoole中使用thinkphp db数据库操作
## 示例
```
require './vendor/autoload.php';

/**
easyswoole 3.3.0之前可以使用的版本

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
});**/

/**
 * easyswoole 3.3.0后必须使用的版本
 */

use szjcomo\mysqliPool\MysqlPool;

$config = new \EasySwoole\Pool\Config();

$arr = [
    'host'                 => '192.168.1.107',
    'port'                 => 3306,
    'user'                 => 'xxx',
    'password'             => 'xxx',
    'database'             => 'xxx',
    'prefix'               => 'xxx',
    'timeout'              => 30,
    'debug'                => true,
    'charset'              => 'utf8'
];

$pool = new MysqlPool($config,$arr);

go(function() use ($pool) {
    $model = $pool->defer();
    $result = $model->name('admin_user')->where('id','in',[1,3,4])->select();
    print_r($result);
    print_r($pool->status());
});


```