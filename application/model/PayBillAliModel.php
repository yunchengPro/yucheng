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

class PayBillAliModel {

	protected $_modelObj;


	public function __construct() {
		$this->_modelObj = Db::Table('PayBillAli');
	}

	public function getField(){
		return [
			"0"=>[
					"name"=>"支付宝交易号",
					"field"=>"trade_no",
				],
			"1"=>[
					"name"=>"商户订单号",
					"field"=>"out_trade_no",
				],
			"2"=>[
					"name"=>"业务类型",
					"field"=>"type",
				],
			"3"=>[
					"name"=>"商品名称",
					"field"=>"body",
				],
			"4"=>[
					"name"=>"创建时间",
					"field"=>"gmt_create",
				],
			"5"=>[
					"name"=>"完成时间",
					"field"=>"gmt_payment",
				],
			"6"=>[
					"name"=>"门店编号",
					"field"=>"businessid",
				],
			"7"=>[
					"name"=>"门店名称",
					"field"=>"businessname",
				],
			"8"=>[
					"name"=>"操作员",
					"field"=>"actuserid",
				],
			"9"=>[
					"name"=>"终端号",
					"field"=>"device_info",
				],
			"10"=>[
					"name"=>"对方账户",
					"field"=>"buyer_logon_id",
				],
			"11"=>[
					"name"=>"订单金额",
					"field"=>"total_amount",
				],
			"12"=>[
					"name"=>"商家实收",
					"field"=>"receipt_amount",
				],
			"13"=>[
					"name"=>"支付宝红包",
					"field"=>"redpacket_amount",
				],
			"14"=>[
					"name"=>"集分宝",
					"field"=>"point_amount",
				],
			"15"=>[
					"name"=>"支付宝优惠",
					"field"=>"voucher_amount",
				],
			"16"=>[
					"name"=>"商家优惠",
					"field"=>"bus_voucher_amount",
				],
			"17"=>[
					"name"=>"券核销金额",
					"field"=>"hevoucher_amount",
				],
			"18"=>[
					"name"=>"券名称",
					"field"=>"hevoucher_name",
				],
			"19"=>[
					"name"=>"商家红包消费金额",
					"field"=>"busredpacket_amount",
				],
			"20"=>[
					"name"=>"卡消费金额",
					"field"=>"card_amount",
				],
			"21"=>[
					"name"=>"退款批次号/请求号",
					"field"=>"request_code",
				],
			"22"=>[
					"name"=>"服务费",
					"field"=>"service_amount",
				],
			"23"=>[
					"name"=>"分润",
					"field"=>"profit_amount",
				],
			"24"=>[
					"name"=>"备注",
					"field"=>"mark",
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
	public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
	    return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
	}

}
?>