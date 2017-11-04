<?php
/**
*
* 产品类型表修改Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 21:32:52Z robert zhao $
*/

namespace app\form\ProModule;
use app\lib\form\BaseForm;

class ProModuleEdit extends BaseForm {
	
	protected $_id 	= null;

	protected $_modulename 	= null;

	protected $_sort 	= null;

	protected $_isdelete 	= null;

			
	protected function getValidations() {
		return array (
			'id' => array (
				array ('not_empty', '非法操作'),
				array ('is_digits', '非法操作')
			),
			'id' => array (
				array ('is_int', '主键ID必须为数字'),
			),
			'modulename' => array (
				array ('max_length', 50, '类型名称长度不能超过50'),
			),
			'sort' => array (
				array ('is_int', '排序必须为数字'),
			),
			'isdelete' => array (
				array ('is_int', '是否删除-1已删除必须为数字'),
			),
		);
	}
}?>