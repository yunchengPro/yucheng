<?php 
namespace app\model\Db;

use app\lib\Db\MysqlDb;

//继承自数据库操作类
class UserDB extends MysqlDb //继承自Mysql的操作
{	
	protected $_tableName = "user"; //表名

	protected $_primary = "id"; //主键名

	protected $_db = "nnh_db"; //Db的配置项 和database.php对应
    
	protected $_table_prefix = ""; //表前缀

	protected $_uname 	= null;

	protected $_passwd 	= null;

	protected $_tel 	= null;

	protected $_totalPage = null;
		

	/**
	 *
	 * 插入用户信息
	 */
	public function add() {
		! is_null($this->_uname) && $data['uname'] = $this->_uname;
		! is_null($this->_passwd) && $data['passwd'] = $this->_passwd;
		! is_null($this->_tel) && $data['tel'] = $this->_tel;
	    return $this->insert($data);
	}

	/**
	 *
	 * 更新用户信息
	 */
	public function modify($id) {
		$data[$this->_pk] = $this->_id = intval($id);
		if (empty($this->_id)) {
			throw new \Exception('要删除的ID不能为空');
			return ;
		}
		! is_null($this->_uname) && $data['uname'] = $this->_uname;
		! is_null($this->_passwd) && $data['passwd'] = $this->_passwd;
		! is_null($this->_tel) && $data['tel'] = $this->_tel;
		return $this->update($data);
	}

	/**
	 * 删除用户信息
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
	 * 获取所有用户信息--分页
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
	 * 获取所有用户信息
	 * @return Ambigous 
	 */
	public function getAll() {
		$where = null; ## TODO
		if (! is_null($where)) {
			$this->where($where);
		}
		return $this->select();
	}
}