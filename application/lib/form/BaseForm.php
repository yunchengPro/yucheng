<?php
/**
 *
 * 自动执行添加Form
 *
 *
 * @copyright  Copyright (c) 2009-2011 广州北居易
 * @version    $Id: 2012-06-09 12:27:49Z robert zhao $
 */
namespace app\lib\form;

use app\lib\form\Validator;

class BaseForm {

	/**
	 * 错误信息
	 * @var string
	 */
	protected $_errMsg = null;
	
	/**
	 * 设置POST值
	 * @param array $data
	 */
	protected function setKey($data) {
		if (is_array($data) && count($data) >0 ) {
			foreach ($data as $key => $val) {
				if ($key && $this->_{$key} === null) {
					$this->_{$key} = $val;
				}
			}
		}
	}
	
	/**
	 *  获取过滤的值
	 * @param string $key
	 */
	public function getValue($key) {
		if (! empty($key)) {
			return $this->_{$key};
		}
	}

	/**
	 * 验证数据
	 * @param array $data
	 */
	public function isValid($data) {
		$this->setKey($data);
		$validations = $this->getValidations();
		if (empty($validations)) {
			return true;
		}
		foreach ($validations as $field => $rules) {
			if (! isset($data[$field]) || empty($data[$field])) {
				if (empty($rules)) {
					continue;
				}
				foreach ($rules as $rule) {
					$validation = array_shift($rule);
					$msg = array_pop($rule);
					if (in_array($validation, array('not_empty', 'not_null'))) {
						if ($msg=="") {
							$this->_errMsg = $field."不能为空";
						}else{
							$this->_errMsg = $msg;
						}
						return false;
					}
				}
			} else {
				foreach ($rules as $idx=> $rule) {
					$validation = array_shift($rule);
					$msg = array_pop($rule);
					array_unshift($rule, $data[$field]);
					$ret = Validator::validateByArgs($validation, $rule);
					if ($ret) {
						continue;
					} else {
						$this->_errMsg = $msg;
						return false;
					}
				}
			}
		}
		return true;
	}
	
	/**
	 * 获取错误信息
	 */
	public function getErr() {
		return $this->_errMsg;
	}
}
?>