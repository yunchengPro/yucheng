<?php
/**
* app首页显示模块明细表类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-07 14:15:46Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class AmoComBonusModel {

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('AmoComBonus');
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

    public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	/**
	 *
	 * app首页显示模块表列表
	 */
	 public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1){
	    return $this->_modelObj->pageList($where,$field,$order,$flag);
	}

	/*
     * 获取单条数据
     * $where 可以是字符串 也可以是数组
     */
    public function getRow($where,$field='*',$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr);
    }
	
	/*
	获取公司余额
	获取公司余额
	 */
	public function getAmount($field="*"){
		return $this->_modelObj->getRow(["id"=>1],$field);
	}

	/*
	获取现金余额
	 */
	public function getBonusAmount(){
		$row = $this->getAmount("bonusamount");
		return intval($row['bonusamount']);
	}

	/*
	扣减现金余额
	 */
	public function DedBonusAmount($amount){
		return $this->_modelObj->update("bonusamount=bonusamount-".intval($amount),["bonusamount"=>[">=",$amount],"id"=>1]);
	}


	/*
	增加现金余额
	 */
	public function AddBonusAmount($amount){
		return $this->_modelObj->update("bonusamount=bonusamount+".intval($amount),["id"=>1]);
	}

	
}
?>