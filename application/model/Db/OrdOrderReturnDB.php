<?php
/**
*
* 退货退款单类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: OrdOrderReturn.php 10319 2017-03-03 16:37:24Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class OrdOrderReturnDB extends MysqlDb {

	protected $_tableName = "ord_order_return";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_returnType 	= null;

	protected $_businessId 	= null;

	protected $_businessName 	= null;

	protected $_orderid 	= null;

	protected $_orderCode 	= null;

	protected $_returnno 	= null;

	protected $_starttime 	= null;
	
	protected $_actiontime = null;
	
	protected $_examinetime = null;

	protected $_endtime 	= null;

	protected $_returnreason 	= null;

	protected $_remark 	= null;

	protected $_images 	= null;

	protected $_orderstatus 	= null;

	protected $_customerid 	= null;

	protected $_customerName 	= null;

	protected $_mobile 	= null;

	protected $_isget 	= null;

	protected $_returnAddress 	= null;

	protected $_expressid 	= null;

	protected $_expressname 	= null;

	protected $_expressnumber 	= null;

	protected $_expressRemark 	= null;

	protected $_expressPic 	= null;

	protected $_returnamount 	= null;

	protected $_freight 	= null;

	protected $_actualamount 	= null;

	protected $_receiveremark 	= null;

	protected $_isdelete 	= null;

	protected $_refusereason 	= null;

	protected $_addressid 	= null;

	protected $_itemsId 	= null;

	protected $_productid 	= null;

	protected $_productname 	= null;

	protected $_productcode 	= null;

	protected $_productnum 	= null;

	protected $_categoryid 	= null;

	protected $_categoryname 	= null;

	protected $_brandid 	= null;

	protected $_brandname 	= null;

	protected $_moduleid 	= null;

	protected $_modulename 	= null;

	protected $_skuid 	= null;

	protected $_skucode 	= null;

	protected $_skudetail 	= null;

	protected $_price 	= null;

	protected $_realproductnum 	= null;

	protected $_orderMoney 	= null;

	protected $_thumb 	= null;

	protected $_auditRemark 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";

	protected $_table_prefix = "";


	/**
	 *
	 * 插入退货退款单
	 */
	public function add() {
		! is_null($this->_returnType) && $data['return_type'] = $this->_returnType;
		! is_null($this->_businessId) && $data['business_id'] = $this->_businessId;
		! is_null($this->_businessName) && $data['business_name'] = $this->_businessName;
		! is_null($this->_orderid) && $data['orderid'] = $this->_orderid;
		! is_null($this->_orderCode) && $data['order_code'] = $this->_orderCode;
		! is_null($this->_returnno) && $data['returnno'] = $this->_returnno;
		! is_null($this->_starttime) && $data['starttime'] = $this->_starttime;
		! is_null($this->_actiontime) && $data['actiontime'] = $this->_actiontime;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_endtime) && $data['endtime'] = $this->_endtime;
		! is_null($this->_returnreason) && $data['returnreason'] = $this->_returnreason;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_images) && $data['images'] = $this->_images;
		! is_null($this->_orderstatus) && $data['orderstatus'] = $this->_orderstatus;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_customerName) && $data['customer_name'] = $this->_customerName;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_isget) && $data['isget'] = $this->_isget;
		! is_null($this->_returnAddress) && $data['return_address'] = $this->_returnAddress;
		! is_null($this->_expressid) && $data['expressid'] = $this->_expressid;
		! is_null($this->_expressname) && $data['expressname'] = $this->_expressname;
		! is_null($this->_expressnumber) && $data['expressnumber'] = $this->_expressnumber;
		! is_null($this->_expressRemark) && $data['express_remark'] = $this->_expressRemark;
		! is_null($this->_expressPic) && $data['express_pic'] = $this->_expressPic;
		! is_null($this->_returnamount) && $data['returnamount'] = $this->_returnamount;
		! is_null($this->_freight) && $data['freight'] = $this->_freight;
		! is_null($this->_actualamount) && $data['actualamount'] = $this->_actualamount;
		! is_null($this->_receiveremark) && $data['receiveremark'] = $this->_receiveremark;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		! is_null($this->_refusereason) && $data['refusereason'] = $this->_refusereason;
		! is_null($this->_addressid) && $data['addressid'] = $this->_addressid;
		! is_null($this->_itemsId) && $data['items_id'] = $this->_itemsId;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_productcode) && $data['productcode'] = $this->_productcode;
		! is_null($this->_productnum) && $data['productnum'] = $this->_productnum;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_brandid) && $data['brandid'] = $this->_brandid;
		! is_null($this->_brandname) && $data['brandname'] = $this->_brandname;
		! is_null($this->_moduleid) && $data['moduleid'] = $this->_moduleid;
		! is_null($this->_modulename) && $data['modulename'] = $this->_modulename;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_skucode) && $data['skucode'] = $this->_skucode;
		! is_null($this->_skudetail) && $data['skudetail'] = $this->_skudetail;
		! is_null($this->_price) && $data['price'] = $this->_price;
		! is_null($this->_realproductnum) && $data['realproductnum'] = $this->_realproductnum;
		! is_null($this->_orderMoney) && $data['order_money'] = $this->_orderMoney;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_auditRemark) && $data['audit_remark'] = $this->_auditRemark;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新退货退款单
	 */
	public function modify($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_returnType) && $data['return_type'] = $this->_returnType;
		! is_null($this->_businessId) && $data['business_id'] = $this->_businessId;
		! is_null($this->_businessName) && $data['business_name'] = $this->_businessName;
		! is_null($this->_orderid) && $data['orderid'] = $this->_orderid;
		! is_null($this->_orderCode) && $data['order_code'] = $this->_orderCode;
		! is_null($this->_returnno) && $data['returnno'] = $this->_returnno;
		! is_null($this->_starttime) && $data['starttime'] = $this->_starttime;
		! is_null($this->_actiontime) && $data['actiontime'] = $this->_actiontime;
		! is_null($this->_examinetime) && $data['examinetime'] = $this->_examinetime;
		! is_null($this->_endtime) && $data['endtime'] = $this->_endtime;
		! is_null($this->_returnreason) && $data['returnreason'] = $this->_returnreason;
		! is_null($this->_remark) && $data['remark'] = $this->_remark;
		! is_null($this->_images) && $data['images'] = $this->_images;
		! is_null($this->_orderstatus) && $data['orderstatus'] = $this->_orderstatus;
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_customerName) && $data['customer_name'] = $this->_customerName;
		! is_null($this->_mobile) && $data['mobile'] = $this->_mobile;
		! is_null($this->_isget) && $data['isget'] = $this->_isget;
		! is_null($this->_returnAddress) && $data['return_address'] = $this->_returnAddress;
		! is_null($this->_expressid) && $data['expressid'] = $this->_expressid;
		! is_null($this->_expressname) && $data['expressname'] = $this->_expressname;
		! is_null($this->_expressnumber) && $data['expressnumber'] = $this->_expressnumber;
		! is_null($this->_expressRemark) && $data['express_remark'] = $this->_expressRemark;
		! is_null($this->_expressPic) && $data['express_pic'] = $this->_expressPic;
		! is_null($this->_returnamount) && $data['returnamount'] = $this->_returnamount;
		! is_null($this->_freight) && $data['freight'] = $this->_freight;
		! is_null($this->_actualamount) && $data['actualamount'] = $this->_actualamount;
		! is_null($this->_receiveremark) && $data['receiveremark'] = $this->_receiveremark;
		! is_null($this->_isdelete) && $data['isdelete'] = $this->_isdelete;
		! is_null($this->_refusereason) && $data['refusereason'] = $this->_refusereason;
		! is_null($this->_addressid) && $data['addressid'] = $this->_addressid;
		! is_null($this->_itemsId) && $data['items_id'] = $this->_itemsId;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productname) && $data['productname'] = $this->_productname;
		! is_null($this->_productcode) && $data['productcode'] = $this->_productcode;
		! is_null($this->_productnum) && $data['productnum'] = $this->_productnum;
		! is_null($this->_categoryid) && $data['categoryid'] = $this->_categoryid;
		! is_null($this->_categoryname) && $data['categoryname'] = $this->_categoryname;
		! is_null($this->_brandid) && $data['brandid'] = $this->_brandid;
		! is_null($this->_brandname) && $data['brandname'] = $this->_brandname;
		! is_null($this->_moduleid) && $data['moduleid'] = $this->_moduleid;
		! is_null($this->_modulename) && $data['modulename'] = $this->_modulename;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_skucode) && $data['skucode'] = $this->_skucode;
		! is_null($this->_skudetail) && $data['skudetail'] = $this->_skudetail;
		! is_null($this->_price) && $data['price'] = $this->_price;
		! is_null($this->_realproductnum) && $data['realproductnum'] = $this->_realproductnum;
		! is_null($this->_orderMoney) && $data['order_money'] = $this->_orderMoney;
		! is_null($this->_thumb) && $data['thumb'] = $this->_thumb;
		! is_null($this->_auditRemark) && $data['audit_remark'] = $this->_auditRemark;
		return $this->update($data);
	}

	/**
	 * 删除退货退款单
	 */
	public function del($id) {
		$data[$this->_primary] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		return $this->delete($data);
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

	/**
	 *
	 * 获取所有退货退款单--分页
	 * @param interger $status
	 */
	public function getAllForPage($page = 0, $pagesize = 20) {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		$this->_totalPage = $this->count();
		return $this->page($page, $pagesize)->order("{$this->_primary} desc")->select();
	}

	/**
	 * 获取所有退货退款单
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}
	
	public function cleanAll() {
				$this->_id 	= null;

				$this->_returnType 	= null;

				$this->_businessId 	= null;

				$this->_businessName 	= null;

				$this->_orderid 	= null;

				$this->_orderCode 	= null;

				$this->_returnno 	= null;

				$this->_starttime 	= null;
				
				$this->_actiontime = null;
				
				$this->_examinetime = null;

				$this->_endtime 	= null;

				$this->_returnreason 	= null;

				$this->_remark 	= null;

				$this->_images 	= null;

				$this->_orderstatus 	= null;

				$this->_customerid 	= null;

				$this->_customerName 	= null;

				$this->_mobile 	= null;

				$this->_isget 	= null;

				$this->_returnAddress 	= null;

				$this->_expressid 	= null;

				$this->_expressname 	= null;

				$this->_expressnumber 	= null;

				$this->_expressRemark 	= null;

				$this->_expressPic 	= null;

				$this->_returnamount 	= null;

				$this->_freight 	= null;

				$this->_actualamount 	= null;

				$this->_receiveremark 	= null;

				$this->_isdelete 	= null;

				$this->_refusereason 	= null;

				$this->_addressid 	= null;

				$this->_itemsId 	= null;

				$this->_productid 	= null;

				$this->_productname 	= null;

				$this->_productcode 	= null;

				$this->_productnum 	= null;

				$this->_categoryid 	= null;

				$this->_categoryname 	= null;

				$this->_brandid 	= null;

				$this->_brandname 	= null;

				$this->_moduleid 	= null;

				$this->_modulename 	= null;

				$this->_skuid 	= null;

				$this->_skucode 	= null;

				$this->_skudetail 	= null;

				$this->_price 	= null;

				$this->_realproductnum 	= null;

				$this->_orderMoney 	= null;

				$this->_thumb 	= null;

				$this->_auditRemark 	= null;

			}
}
?>