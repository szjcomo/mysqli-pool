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

use EasySwoole\Pool\AbstractPool;
use EasySwoole\Pool\Config as poolConfig;
use szjcomo\mysqli\Config as mysqliConfig;

/**
 * 实现mysql数据库链接池
 */
class MysqlPool extends AbstractPool
{

	protected $mysqlConfig = [];
	/**
	 * [__construct 实现mysql数据库链接池]
	 * @author 	   szjcomo
	 * @createTime 2019-11-12
	 * @param      [type]     $config [description]
	 */
	public function __construct(poolConfig $pool_config,array $mysql_config)
	{
		parent::__construct($pool_config);
		$this->mysqlConfig = new mysqliConfig($mysql_config);
	}
	/**
	 * [createObject 实例化对象]
	 * @author 	   szjcomo
	 * @createTime 2019-11-12
	 * @return     [type]     [description]
	 */
    protected function createObject()
    {
        return new Mysqli($this->mysqlConfig);
    }
}