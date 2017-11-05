<?php
/**
*
* 商品分类添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 18:07:41Z robert zhao $
*/
namespace app\form\ProCategoty;
use app\lib\form\BaseForm;

class ProCategotyAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_parentId 	= null;

	protected $_name 	= null;

	protected $_status 	= null;

			
	protected function getValidations() {
		return array (
			'parentId' => array (
				array ('is_digits', '上级分类id必须为数字'),
			),
			'name' => array (
				array ('max_length', 30, '分类名称长度不能超过30'),
			),
			'status' => array (
				array ('is_digits', '状态必须为数字'),
			),
		);
	}
}?>