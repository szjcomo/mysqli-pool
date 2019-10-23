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


use EasySwoole\Component\Pool\PoolObjectInterface;
use szjcomo\mysqli\Mysqli;

class Connection extends Mysqli implements PoolObjectInterface
{
    function gc()
    {
        try{
            $this->rollback();
        }catch (\Throwable $throwable){
            trigger_error($throwable->getMessage());
        }
        $this->resetDbStatus();
        $this->getMysqlClient()->close();
    }

    function objectRestore()
    {
        try{
            $this->rollback();
        }catch (\Throwable $throwable){
            trigger_error($throwable->getMessage());
        }
        $this->resetDbStatus();
    }

    function beforeUse(): bool
    {
        return $this->getMysqlClient()->connected;
    }
}