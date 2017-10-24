<?php
/**
*
* 购物车类
*
* @copyright  Copyright (c) 2010-2014 雲牛匯(深圳)科技有限公司
* @version    $Id: ShoppingCart.php 10319 2017-03-02 14:43:24Z robert $
*/
namespace app\Model\Db;
use app\lib\Db\MysqlDb;

class ShoppingCartDB extends MysqlDb {

	protected $_tableName = "ord_shoppingcart";

	protected $_primary = "id";

	protected $_id 	= null;

	protected $_customerid 	= null;

	protected $_businessid 	= null;

	protected $_productid 	= null;

	protected $_productnum 	= null;

	protected $_skuid 	= null;

	protected $_skuCode 	= null;

	protected $_addtime 	= null;

	protected $_totalPage = null;

	protected $_db = "nnh_db";


	/**
	 *
	 * 插入购物车
	 */
	public function add() {
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productnum) && $data['productnum'] = $this->_productnum;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_skuCode) && $data['sku_code'] = $this->_skuCode;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新购物车
	 */
	public function modify($id) {
		$data[$this->_pk] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_customerid) && $data['customerid'] = $this->_customerid;
		! is_null($this->_businessid) && $data['businessid'] = $this->_businessid;
		! is_null($this->_productid) && $data['productid'] = $this->_productid;
		! is_null($this->_productnum) && $data['productnum'] = $this->_productnum;
		! is_null($this->_skuid) && $data['skuid'] = $this->_skuid;
		! is_null($this->_skuCode) && $data['sku_code'] = $this->_skuCode;
		! is_null($this->_addtime) && $data['addtime'] = $this->_addtime;
		return $this->update($data);
	}

	/**
	 * 删除购物车
	 */
	public function del($id) {
		$data[$this->_pk] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		return $this->where($data)->delete();
	}

	/**
	 *
	 * 根据ID获取一行
	 * @param interger $id
	 */
	public function getById($id) {
		$this->_id = is_null($id)? $this->_id : $id;
		return $this->where(array($this->_pk => $this->_id))->find();
	}

	/**
	 *
	 * 获取所有购物车--分页
	 * @param interger $status
	 */
	public function getAllForPage($page = 0, $pagesize = 20) {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		$this->_totalPage = $this->count();
		return $this->page($page, $pagesize)->order("{$this->_pk} desc")->select();
	}

	/**
	 * 获取所有购物车
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}



    /**
     * [getCateData 获取分类数据]
     * @Author   ISir<673638498@qq.com>
     * @Date 2017-03-01 
     * @return [type] [description]
     */
    public static function getCateData(){

        $topCate = Db::Table('ProCategory')->getList(['parent_id'=>0,'status'=>1]);

        foreach ($topCate as $key => $value) {
            $sonCate = Db::Table('ProCategory')->getList(['parent_id'=>$value['id'],'status'=>1]);
            $topCate[$key]['sonCate'] = $sonCate;
        }
        return $topCate;
    }


}
?>