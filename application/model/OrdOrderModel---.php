<?php
/**
* 商品类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-02 14:33:34Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class OrdOrderModel {

	protected $_modelObj;

	public function __construct() {
		$this->_modelObj = Db::Table('OrdOrder');
	}

	/**
	 * 生成订单编号
	 * @Author   zhuangqm
	 * @DateTime 2017-03-03T16:30:47+0800
	 * @return   [type]                   [description]
	 */
	public function getOrderNo(){
		return "NNH".date("YmdHis").rand(100000,999999);
	}

	/**
	 *
	 * 添加
	 */
	public function add($data) {
		return $this->_modelObj->insert($data);
	}

	/**
	 *
	 * 更新
	 */
	public function modify($id) {
		
		return $this->_modelObj->modify($id);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null,$field="*") {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getRow(["id"=>$id],$field);
		return $this->_dataInfo;
	}

	/**
	 *
	 * 列表
	 */
	public function getList($page, $pagesize) {
		return $this->_modelObj->getAllForPage($page, $pagesize);
	}

	/**
	 * 获取所有
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}

}
?>