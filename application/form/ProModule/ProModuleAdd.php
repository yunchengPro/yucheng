<?php
/**
*
* 产品类型表添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 21:32:52Z robert zhao $
*/
namespace app\form\ProModule;
use app\lib\form\BaseForm;

class ProModuleAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_modulename 	= null;

	protected $_sort 	= null;

	protected $_isdelete 	= null;

			
	protected function getValidations() {
		return array (
			'modulename' => array (
				array ('max_length', 50, '类型名称长度不能超过50'),
			),
			'sort' => array (
				array ('is_digits', '排序必须为数字'),
			),
			'isdelete' => array (
				array ('is_digits', '是否删除-1已删除必须为数字'),
			),
		);
	}
}?>