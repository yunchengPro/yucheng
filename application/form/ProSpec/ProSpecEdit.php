<?php
/**
*
* 商品规格表修改Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:07:15Z robert zhao $
*/

namespace app\form\ProSpec;
use app\lib\form\BaseForm;

class ProSpecEdit extends BaseForm {
	
	protected $_id 	= null;

	protected $_specname 	= null;

	protected $_sort 	= null;

	protected $_classId 	= null;

	protected $_className 	= null;

			
	protected function getValidations() {
		return array (
			'id' => array (
				array ('not_empty', '非法操作'),
				array ('is_digits', '非法操作')
			),
			'specname' => array (
				array ('not_empty', '规格名称不能为空'),
				array ('max_length', 50, '规格名称长度不能超过50'),
			),
			'sort' => array (
				array ('is_int', '排序必须为数字'),
			),
			'classId' => array (
				array ('is_int', '商品分类id必须为数字'),
			),
			'className' => array (
				array ('max_length', 50, '商品分类名称长度不能超过50'),
			),
		);
	}
}?>