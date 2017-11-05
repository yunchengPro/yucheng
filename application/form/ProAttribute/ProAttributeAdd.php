<?php
/**
*
* 商品属性表添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 21:38:30Z robert zhao $
*/
namespace app\form\ProAttribute;
use app\lib\form\BaseForm;

class ProAttributeAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_attrName 	= null;

	protected $_sort 	= null;

	protected $_isdelete 	= null;

			
	protected function getValidations() {
		return array (
			'attrName' => array (
				array ('max_length', 100, '属性名称长度不能超过100'),
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