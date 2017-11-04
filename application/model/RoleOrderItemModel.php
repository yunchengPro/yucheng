<?php
/**
* 订单明细表（对应商品）类
*
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 16:27:39Z robert zhao $
*/

namespace app\model;
use app\lib\Db;

class RoleOrderItemModel {

	protected $_id 	= null;

	protected $_orderid 	= null;

	protected $_orderCode 	= null;

	protected $_businessid 	= null;

	protected $_businessname 	= null;

	protected $_productid 	= null;

	protected $_productname 	= null;

	protected $_productcode 	= null;

	protected $_externalcode 	= null;

	protected $_productnum 	= null;

	protected $_categoryid 	= null;

	protected $_categoryname 	= null;

	protected $_skuid 	= null;

	protected $_skucode 	= null;

	protected $_externalskucode 	= null;

	protected $_thumb 	= null;

	protected $_prouctprice 	= null;

	protected $_bullamount 	= null;

	protected $_addtime 	= null;

	protected $_returnType 	= null;

	protected $_returnnum 	= null;

	protected $_intendedReturn 	= null;

	protected $_returnMoney 	= null;

	protected $_intendedMoney 	= null;
	
	protected $_returnBull = null;
	
	protected $_intendedBull = null;

	protected $_evaluate 	= null;

	protected $_remark 	= null;
	
	protected $_enable = null;

	protected $_modelObj;

	protected $_totalPage = null;
	
	protected $_dataInfo = null;

	public function __construct() {
		$this->_modelObj = Db::Table('RoleOrderItem');
	}

	/**
	 *
	 * 添加订单明细表（对应商品）
	 */
	public function insert($data) {
		
		//$this->setData($data);
		return $this->_modelObj->insert($data);
	}

	public function setData($data){
		
		foreach($data as $k=>$v){
			$this->_modelObj->{"_".$k} = $v;
		}
	}

	/**
	 *
	 * 更新订单明细表（对应商品）
	 */
// 	public function modify($id) {
// 		$this->_modelObj->_orderid  = $this->_orderid;
// 		$this->_modelObj->_orderCode  = $this->_orderCode;
// 		$this->_modelObj->_businessid  = $this->_businessid;
// 		$this->_modelObj->_businessname  = $this->_businessname;
// 		$this->_modelObj->_productid  = $this->_productid;
// 		$this->_modelObj->_productname  = $this->_productname;
// 		$this->_modelObj->_productcode  = $this->_productcode;
// 		$this->_modelObj->_externalcode  = $this->_externalcode;
// 		$this->_modelObj->_productnum  = $this->_productnum;
// 		$this->_modelObj->_categoryid  = $this->_categoryid;
// 		$this->_modelObj->_categoryname  = $this->_categoryname;
// 		$this->_modelObj->_skuid  = $this->_skuid;
// 		$this->_modelObj->_skucode  = $this->_skucode;
// 		$this->_modelObj->_externalskucode  = $this->_externalskucode;
// 		$this->_modelObj->_thumb  = $this->_thumb;
// 		$this->_modelObj->_prouctprice  = $this->_prouctprice;
// 		$this->_modelObj->_bullamount  = $this->_bullamount;
// 		$this->_modelObj->_addtime  = $this->_addtime;
// 		$this->_modelObj->_returnType  = $this->_returnType;
// 		$this->_modelObj->_returnnum  = $this->_returnnum;
// 		$this->_modelObj->_intendedReturn  = $this->_intendedReturn;
// 		$this->_modelObj->_returnMoney  = $this->_returnMoney;
// 		$this->_modelObj->_intendedMoney  = $this->_intendedMoney;
// 		$this->_modelObj->_evaluate  = $this->_evaluate;
// 		$this->_modelObj->_remark  = $this->_remark;
// 		return $this->_modelObj->modify($id);
// 	}

	public function modify($updateData, $where) {
	    return $this->_modelObj->update($updateData,$where);
	}

	/**
	 *
	 * 详细
	 */
	public function getById($id = null) {
		$this->_id = is_null($id)? $this->_id : $id;
		$this->_dataInfo = $this->_modelObj->getById($this->_id);
		return $this->_dataInfo;
	}

	 /*
    * 获取单条数据
    * $where 可以是字符串 也可以是数组
    */
    public function getRow($where,$field='*',$order='',$otherstr=''){
        return $this->_modelObj->getRow($where,$field,$order,$otherstr='');
    }
    
    /**
    * @user 根据订单编号获取商品规格及个数的信息
    * @param $orderno 订单编号
    * @author jeeluo
    * @date 2017年3月17日上午10:34:10
    * @date 2017年5月8日15:59:30 ISir修改
    */
    public function getAllItemDetailByOrderNO($data) {
        $itemlist = $this->_modelObj->getList(array("orderno" => $data['orderno']), "skuid, productnum, productid,productname,prouctprice,bullamount,skudetail");
        //print_r($itemlist);
        // foreach ($itemlist as $key => $item) {
        //     $goods = self::getItemDetailBySkuid($item);
        //     $itemlist[$key]['skudetail'] = $goods['skudetail'];
        //     $itemlist[$key]['productname'] = $goods['productname'];
        //     $itemlist[$key]['prouctprice'] = $goods['prouctprice'];
        //     $itemlist[$key]['bullamount'] = $goods['bullamount'];
        // }
        return $itemlist;
    }
    
    /**
    * @user 单个商品的规格
    * @param 
    * @author jeeluo
    * @date 2017年3月18日下午3:10:08
    */
    public function getItemDetailBySkuid($data) {
        $goods = array();
        if(!empty($data['skuid'])) {
            // 有商品规格
            $specOBJ = new ProProductSpecModel();
            // $goods = $specOBJ->setId($data['skuid'])->getById($data['skuid'], "productname, prouctprice, bullamount, spec");
            $goods = $specOBJ->getRow(array("id" => $data['skuid']), "productname,prouctprice,bullamount,spec,productimage as thumb");
            $decodespec = json_decode($goods['spec']);
            $goods['skudetail'] = '';
            foreach ($decodespec as $v) {
                $goods['skudetail'] .= $v->name."：".$v->value.',';
            }
        } else {
            // 无商品规格
            $proOBJ = new ProProductModel();
            // $goods = $proOBJ->setId($data['productid'])->getById($data['productid'], "productname, prouctprice, bullamount");
            $goods = $proOBJ->getRow(array("id" => $data['productid']), "productname,prouctprice,bullamount,thumb");
            $goods['skudetail'] = '';
        }
        $goods['skudetail'] = substr($goods['skudetail'], 0, -1);
        return $goods;
    }
    
    /*
    * 获取多行记录
    */
    public function getList($where,$field='*',$order='',$limit=0,$offset=0,$otherstr=''){
        return $this->_modelObj->getList($where,$field,$order,$limit,$offset,$otherstr);
    }

    /*
    * 分页列表
    * $flag = 0 表示不返回总条数
    */
    public function pageList($where,$field='*',$order='',$flag=1,$page='',$pagesize=''){
        return $this->_modelObj->pageList($where,$field,$order,$flag,$page,$pagesize);
    }

    /*
    * 更新数据
    * $updateData = array()
    */
    public function update($updateData,$where,$limit=''){
        return $this->_modelObj->update($updateData,$where,$limit='');
    }
    /*
    * 删除数据
    */
    public function delete($where,$limit=''){
        return $this->_modelObj->delete($where,$limit);
    }
    
    /**
    * @user 项目所需金额
    * @param 
    * @author jeeluo
    * @date 2017年3月10日下午7:58:33
    */
    public function getItemPrice($params) {
        $itemInfo = $this->getRow(array("orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']), "prouctprice, bullamount, productnum");
        
        $OrdOrderReturn = new OrdOrderReturnModel();
        // 查看退单审核通过的个数
        $returnList = $OrdOrderReturn->getRow(array("order_code"=>$params['orderno'],"productid"=>$params['productid'],"skuid"=>$params['skuid'],"orderstatus"=>array(array("=",4), array("=",12,"or"), array("=",14,"or"))), "SUM(productnum) as productnum ");
       
        if(!empty($returnList)) {
            $itemInfo['productnum'] += $returnList['productnum'];
        }
        
        $result['prouctprice'] = $itemInfo['prouctprice'] * $itemInfo['productnum'];
        $result['bullamount'] = $itemInfo['bullamount'] * $itemInfo['productnum'];
        
        $result['productprice'] = $itemInfo['prouctprice'];
        $result['productbull'] = $itemInfo['bullamount'];
        
        return $result;
    }
    
    /**
    * @user 判断订单项目是否存在
    * @param 
    * @author jeeluo
    * @date 2017年3月10日下午7:58:11
    */
    public function getIsOrderItem($params) {
        $itemInfo = $this->getRow(array("orderno" => $params['orderno'], "productid" => $params['productid'], "skuid" => $params['skuid']));
        
        if(!empty($itemInfo)) {
            return true;
        }
        return false;
    }



	/**
	 * 获取所有订单明细表（对应商品）
	 */
	public function getAll() {
		return $this->_modelObj->getAll();
	}



	/**
	 *
	 * 删除订单明细表（对应商品）
	 */
	public function del($id) {
		return $this->_modelObj->del($id);
	}


	/**
	 * 设置主键id
	 *
	 */
	public function setId($id) {
		$this->_id = $id;
		return $this;
	}

	/**
	 * 设置订单id
	 *
	 */
	public function setOrderid($orderid) {
		$this->_orderid = $orderid;
		return $this;
	}

	/**
	 * 设置订单编号
	 *
	 */
	public function setOrderCode($orderCode) {
		$this->_orderCode = $orderCode;
		return $this;
	}

	/**
	 * 设置商店ID
	 *
	 */
	public function setBusinessid($businessid) {
		$this->_businessid = $businessid;
		return $this;
	}

	/**
	 * 设置商店名称
	 *
	 */
	public function setBusinessname($businessname) {
		$this->_businessname = $businessname;
		return $this;
	}

	/**
	 * 设置商品ID
	 *
	 */
	public function setProductid($productid) {
		$this->_productid = $productid;
		return $this;
	}

	/**
	 * 设置商品名称
	 *
	 */
	public function setProductname($productname) {
		$this->_productname = $productname;
		return $this;
	}

	/**
	 * 设置商品编码
	 *
	 */
	public function setProductcode($productcode) {
		$this->_productcode = $productcode;
		return $this;
	}

	/**
	 * 设置商品编码SPU
	 *
	 */
	public function setExternalcode($externalcode) {
		$this->_externalcode = $externalcode;
		return $this;
	}

	/**
	 * 设置购买数量
	 *
	 */
	public function setProductnum($productnum) {
		$this->_productnum = $productnum;
		return $this;
	}

	/**
	 * 设置商品分类ID
	 *
	 */
	public function setCategoryid($categoryid) {
		$this->_categoryid = $categoryid;
		return $this;
	}

	/**
	 * 设置分类名称
	 *
	 */
	public function setCategoryname($categoryname) {
		$this->_categoryname = $categoryname;
		return $this;
	}

	/**
	 * 设置Sku的ID
	 *
	 */
	public function setSkuid($skuid) {
		$this->_skuid = $skuid;
		return $this;
	}

	/**
	 * 设置Sku编码
	 *
	 */
	public function setSkucode($skucode) {
		$this->_skucode = $skucode;
		return $this;
	}

	/**
	 * 设置商品编码SKU
	 *
	 */
	public function setExternalskucode($externalskucode) {
		$this->_externalskucode = $externalskucode;
		return $this;
	}

	/**
	 * 设置商品主图
	 *
	 */
	public function setThumb($thumb) {
		$this->_thumb = $thumb;
		return $this;
	}

	/**
	 * 设置商品价格
	 *
	 */
	public function setProuctprice($prouctprice) {
		$this->_prouctprice = $prouctprice;
		return $this;
	}

	/**
	 * 设置商品订单牛币数
	 *
	 */
	public function setBullamount($bullamount) {
		$this->_bullamount = $bullamount;
		return $this;
	}

	/**
	 * 设置添加时间
	 *
	 */
	public function setAddtime($addtime) {
		$this->_addtime = $addtime;
		return $this;
	}

	/**
	 * 设置退款退货状态0无1退货2退款
	 *
	 */
	public function setReturnType($returnType) {
		$this->_returnType = $returnType;
		return $this;
	}

	/**
	 * 设置已退货数量
	 *
	 */
	public function setReturnnum($returnnum) {
		$this->_returnnum = $returnnum;
		return $this;
	}

	/**
	 * 设置待退货数量
	 *
	 */
	public function setIntendedReturn($intendedReturn) {
		$this->_intendedReturn = $intendedReturn;
		return $this;
	}

	/**
	 * 设置已退货金额
	 *
	 */
	public function setReturnMoney($returnMoney) {
		$this->_returnMoney = $returnMoney;
		return $this;
	}

	/**
	 * 设置待退货金额
	 *
	 */
	public function setIntendedMoney($intendedMoney) {
		$this->_intendedMoney = $intendedMoney;
		return $this;
	}
	
	public function setReturnBull($returnBull) {
	    $this->_returnBull = $returnBull;
	    return $this;
	}
	
	public function setIntendedBull($intendedBull) {
	    $this->_intendedBull = $intendedBull;
	    return $this;
	}

	/**
	 * 设置是否评价0未评价1个人评价2自动评价
	 *
	 */
	public function setEvaluate($evaluate) {
		$this->_evaluate = $evaluate;
		return $this;
	}

	/**
	 * 设置商品备注
	 *
	 */
	public function setRemark($remark) {
		$this->_remark = $remark;
		return $this;
	}
	
	public function setEnable($enable) {
	    $this->_enable = $enable;
	    return $this;
	}

	public static function getModelObj() {
		return new OrdOrderItemDB();
	}

	public function getTotalPage() {
		return intval($this->_modelObj->_totalPage);
	}
}
?>