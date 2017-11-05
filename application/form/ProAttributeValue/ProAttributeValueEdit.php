<?php
/**
*
* 商品属性值表修改Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 21:38:48Z robert zhao $
*/

namespace app\form\ProAttributeValue;
use app\lib\form\BaseForm;

class ProAttributeValueEdit extends BaseForm {
	
	protected $_id 	= null;

	protected $_attrId 	= null;

	protected $_attrValueName 	= null;

	protected $_sort 	= null;

			
	protected function getValidations() {
		return array (
			'id' => array (
				array ('not_empty', '非法操作'),
				array ('is_digits', '非法操作')
			),
			'id' => array (
				array ('is_int', '主键id必须为数字'),
			),
			'sort' => array (
				array ('is_int', '排序必须为数字'),
			),
		);
	}
}?>