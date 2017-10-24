<?php
/**
* 对账单
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-07-03 11:55:00Z  $
*/

namespace app\model;
use app\lib\Db;

class PayBillWeixinModel {

	protected $_modelObj;


	public function __construct() {
		$this->_modelObj = Db::Table('PayBillWeixin');
	}

	public function getField(){
		return [
			"0"=>[
					"name"=>"交易时间",
					"field"=>"time_end",
				],
			"1"=>[
					"name"=>"公众账号ID",
					"field"=>"appid",
				],
			"2"=>[
					"name"=>"商户号",
					"field"=>"mch_id",
				],
			"3"=>[
					"name"=>"子商户号",
					"field"=>"mch_ch_id",
				],
			"4"=>[
					"name"=>"设备号",
					"field"=>"device_info",
				],
			"5"=>[
					"name"=>"微信订单号",
					"field"=>"transaction_id",
				],
			"6"=>[
					"name"=>"商户订单号",
					"field"=>"out_trade_no",
				],
			"7"=>[
					"name"=>"用户标识",
					"field"=>"openid",
				],
			"8"=>[
					"name"=>"交易类型",
					"field"=>"trade_type",
				],
			"9"=>[
					"name"=>"交易状态",
					"field"=>"trade_state",
				],
			"10"=>[
					"name"=>"付款银行",
					"field"=>"bank_type",
				],
			"11"=>[
					"name"=>"货币种类",
					"field"=>"fee_type",
				],
			"12"=>[
					"name"=>"总金额",
					"field"=>"total_fee",
				],
			"13"=>[
					"name"=>"企业红包金额",
					"field"=>"total_fee_redpacket",
				],
			"14"=>[
					"name"=>"微信退款单号",
					"field"=>"refund_id",
				],
			"15"=>[
					"name"=>"商户退款单号",
					"field"=>"out_refund_no",
				],
			"16"=>[
					"name"=>"退款金额",
					"field"=>"refund_fee",
				],
			"17"=>[
					"name"=>"企业红包退款金额",
					"field"=>"refund_total_fee_redpacket",
				],
			"18"=>[
					"name"=>"退款类型",
					"field"=>"refund_type",
				],
			"19"=>[
					"name"=>"退款状态",
					"field"=>"refund_state",
				],
			"20"=>[
					"name"=>"商品名称",
					"field"=>"body",
				],
			"21"=>[
					"name"=>"商户数据包",
					"field"=>"body_data",
				],
			"22"=>[
					"name"=>"手续费",
					"field"=>"service_charge",
				],
			"23"=>[
					"name"=>"费率",
					"field"=>"rate",
				],
		];
	}

	public function insert($insertData) {
    	return $this->_modelObj->insert($insertData);
    }

    public function update($data, $where) {
	    return $this->_modelObj->update($data, $where);
	}

	public function delete($where,$limit=''){
		return $this->_modelObj->delete($where,$limit);
    }

	public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
		return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
	}

	public function getRow($where,$field='*',$order='',$otherstr=''){
	    return $this->_modelObj->getRow($where,$field,$order,$otherstr);
	}

	/*
	 * 分页列表
	 * $flag = 0 表示不返回总条数
	 */
	public function pageList($where,$field='*',$order='',$flag=1){
	    return $this->_modelObj->pageList($where,$field,$order,$flag);
	}

}
?>