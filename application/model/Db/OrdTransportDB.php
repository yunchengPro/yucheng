<?php
/**
*
* 运费模板扩展表类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdTransport.php 10319 2017-03-03 16:44:32Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdTransportDB extends MysqlDb {

	protected $_tableName = "ord_transport";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_businessId 	= null;

	protected $_transportId 	= null;

	protected $_transportTitle 	= null;

	protected $_areaId 	= null;

	protected $_topAreaId 	= null;

	protected $_areaName 	= null;

	protected $_snum 	= null;

	protected $_sprice 	= null;

	protected $_xnum 	= null;

	protected $_xprice 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";

	public function __construct() {
	    $this->_fields = ['business_id','title','valuation_type','transport_type', 'addtime'];
	    //$this->_auto   = [array('addtime', 'function', 'time')];
	}

	/**
	 *
	 * 根据ID获取一行
	 * @param interger $id
	 */
	public function getById($id) {
		$this->_id = is_null($id)? $this->_id : $id;
		return $this->getRow(array($this->_primary => $this->_id));
	}

	
	public function cleanAll() {
				$this->_id 	= null;

				$this->_businessId 	= null;

				$this->_transportId 	= null;

				$this->_transportTitle 	= null;

				$this->_areaId 	= null;

				$this->_topAreaId 	= null;

				$this->_areaName 	= null;

				$this->_snum 	= null;

				$this->_sprice 	= null;

				$this->_xnum 	= null;

				$this->_xprice 	= null;

			}
}
?>