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

use EasySwoole\Pool\ObjectInterface;
use szjcomo\mysqli\Mysqli as comoMysqli;

/**
 * 定义mysqli对象
 */
class Mysqli extends comoMysqli implements ObjectInterface
{
	/**
	 * [gc 执行垃圾回收机制]
	 * @author 	   szjcomo
	 * @createTime 2019-11-12
	 * @return     [type]     [description]
	 */
    function gc()
    {
        
    }
    /**
     * [objectRestore 使用后,free的时候会执行]
     * @author 	   szjcomo
     * @createTime 2019-11-12
     * @return     [type]     [description]
     */
    function objectRestore()
    {
        
    }
    /**
     * [beforeUse 使用前调用,当返回true，表示该对象可用。返回false，该对象失效，需要回收]
     * @author 	   szjcomo
     * @createTime 2019-11-12
     * @return     [type]     [description]
     */
    function beforeUse(): ?bool
    {
        /*
         * 取出连接池的时候，若返回false，则当前对象被弃用回收
         */
        return true;
    }
    /**
     * [who 返回对象信息]
     * @author 	   szjcomo
     * @createTime 2019-11-12
     * @return     [type]     [description]
     */
    public function who()
    {
        return spl_object_id($this);
    }
}