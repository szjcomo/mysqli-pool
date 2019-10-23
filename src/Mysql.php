<?php
/**
 * |-----------------------------------------------------------------------------------
 * @Copyright (c) 2014-2018, http://www.sizhijie.com. All Rights Reserved.
 * @Website: www.sizhijie.com
 * @Version: 思智捷信息科技有限公司
 * @Author : szjcomo 
 * |-----------------------------------------------------------------------------------
 */

namespace szjcomo\mysqliPool;

use EasySwoole\Component\Pool\AbstractPool;
use EasySwoole\Component\Pool\PoolConf;
use EasySwoole\Component\Pool\PoolManager;
use EasySwoole\Component\Singleton;
use szjcomo\mysqli\Config;

//链接池的实现
class Mysql {
    use Singleton;
    private $list = [];
    function register(string $name, Config $config): PoolConf {
        if (isset($this->list[$name])) {
            //已经注册，则抛出异常
            throw new \Exception("mysqlpool:{$name} is already been register");
        }
        /*
        * 绕过去实现动态class
        */
        $class = 'C' . self::character(16);
        $classContent = '<?php
                namespace szjcomo\mysqliPool;
                use EasySwoole\Component\Pool\AbstractPool;
                
                class ' . $class . ' extends AbstractPool {
                    protected function createObject()
                    {
                        return new Connection($this->getConfig()->getExtraConf());
                    }
                }';
        $file = sys_get_temp_dir() . "/{$class}.php";
        file_put_contents($file, $classContent);
        require_once $file;
        unlink($file);
        $class = "szjcomo\\mysqliPool\\{$class}";
        $poolConfig = PoolManager::getInstance()->register($class);
        $poolConfig->setExtraConf($config);
        $this->list[$name] = [
            'class'  => $class,
            'config' => $config
        ];
        return $poolConfig;
    }

    /**
     * [character 获取随机字符串]
     * @Author   szjcomo
     * @DateTime 2019-10-23
     * @param    int|integer $len [description]
     * @return   [type]           [description]
     */
    Protected static function character($length = 16,$alphabet = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789'):string{
        mt_srand();
        // 重复字母表以防止生成长度溢出字母表长度
        if ($length >= strlen($alphabet)) {
            $rate = intval($length / strlen($alphabet)) + 1;
            $alphabet = str_repeat($alphabet, $rate);
        }
        // 打乱顺序返回
        return substr(str_shuffle($alphabet), 0, $length);
    }

    /**
     * [defer 获取一个链接并自动回收 建议使用]
     * @Author   szjcomo
     * @DateTime 2019-10-23
     * @param    string     $name    [description]
     * @param    [type]     $timeout [description]
     * @return   [type]              [description]
     */
    static function defer(string $name, $timeout = null): ?Connection{
        $pool = static::getInstance()->pool($name);
        if ($pool) {
            return $pool::defer($timeout);
        } else {
            return null;
        }
    }
    /**
     * [invoker 获取一个链接 手动回收]
     * @Author   szjcomo
     * @DateTime 2019-10-23
     * @param    string     $name    [description]
     * @param    callable   $call    [description]
     * @param    float|null $timeout [description]
     * @return   [type]              [description]
     */
    static function invoker(string $name, callable $call, float $timeout = null){
        $pool = static::getInstance()->pool($name);
        if ($pool) {
            return $pool::invoke($call, $timeout);
        } else {
            return null;
        }
    }
    /**
     * [pool 获取一个链接 手动回收]
     * @Author   szjcomo
     * @DateTime 2019-10-23
     * @param    string     $name [description]
     * @return   [type]           [description]
     */
    Public function pool(string $name): ?AbstractPool{
        if (isset($this->list[$name])) {
            $item = $this->list[$name];
            if ($item instanceof AbstractPool) {
                return $item;
            } else {
                $class = $item['class'];
                $pool = PoolManager::getInstance()->getPool($class);
                $this->list[$name] = $pool;
                return $this->pool($name);
            }
        } else {
            return null;
        }
    }
}
