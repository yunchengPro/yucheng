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

class AmoComAmountModel {

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('AmoComAmount');
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
	public function getCashAmount(){
		$row = $this->getAmount("cashamount");
		return intval($row['cashamount']);
	}

	/*
	获取收益现金余额
	 */
	public function getProfitAmount(){
		$row = $this->getAmount("profitamount");
		return intval($row['profitamount']);
	}

	/*
	获取牛豆余额
	 */
	public function getBullAmount(){
		$row = $this->getAmount("bullamount");
		return intval($row['bullamount']);
	}

	/*
	扣减现金余额
	 */
	public function DedCashAmount($amount){
		return $this->_modelObj->update("cashamount=cashamount-".intval($amount),["cashamount"=>[">=",$amount],"id"=>1]);
	}

	/*
	扣减商家现金余额
	 */
	public function DedBusAmount($amount){
		return $this->_modelObj->update("busamount=busamount-".intval($amount),["busamount"=>[">=",$amount],"id"=>1]);
	}

	/*
	扣减消费余额
	 */
	public function DedConAmount($amount){
		return $this->_modelObj->update("conamount=conamount-".intval($amount),["conamount"=>[">=",$amount],"id"=>1]);
	}

	/*
	扣减积分余额
	 */
	public function DedIntAmount($amount){
		return $this->_modelObj->update("conamount=conamount-".intval($amount),["conamount"=>[">=",$amount],"id"=>1]);
	}

	/*
	增加现金余额
	 */
	public function AddCashAmount($amount){
		return $this->_modelObj->update("cashamount=cashamount+".intval($amount),["id"=>1]);
	}

	/*
	增加收益现金余额
	 */
	public function AddBusAmount($amount){
		return $this->_modelObj->update("busamount=busamount+".intval($amount),["id"=>1]);
	}

	/*
	增加牛豆余额
	 */
	public function AddConAmount($amount){
		return $this->_modelObj->update("conamount=conamount+".intval($amount),["id"=>1]);
	}

	/*
	增加牛豆余额
	 */
	public function AddIntAmount($amount){
		return $this->_modelObj->update("intamount=intamount+".intval($amount),["id"=>1]);
	}

	/*
	增加现金余额
	 */
	public function AddCashAmountUser($amount,$userid){
		return $this->_modelObj->update("cashamount=cashamount+".intval($amount),["id"=>$userid]);
	}

	/*
	增加手续费余额
	 */
	public function AddCounterAmount($amount){
		return $this->_modelObj->update("counteramount=counteramount+".intval($amount),["id"=>1]);
	}

	/*
	扣减手续费余额
	 */
	public function DedCounterAmount($amount){
		return $this->_modelObj->update("counteramount=counteramount-".intval($amount),["id"=>1]);
	}

	/*
	增加慈善余额
	 */
	public function AddCharitableAmount($amount){
		return $this->_modelObj->update("charitableamount=charitableamount+".intval($amount),["id"=>1]);
	}

	/*
	扣减慈善余额
	 */
	public function DedCharitableAmount($amount){
		return $this->_modelObj->update("charitableamount=charitableamount-".intval($amount),["id"=>1]);
	}
}
?>