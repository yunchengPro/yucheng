<?php
/**
*
* 产品类型分类关系表修改Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 21:34:19Z robert zhao $
*/

namespace app\form\ProModuleCategoryRelation;
use app\lib\form\BaseForm;

class ProModuleCategoryRelationEdit extends BaseForm {
	
	protected $_id 	= null;

	protected $_moduleId 	= null;

	protected $_categoryId 	= null;

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
			'moduleId' => array (
				array ('is_int', '类型id pro_module主键必须为数字'),
			),
			'categoryId' => array (
				array ('is_int', '分类id pro_category主键必须为数字'),
			),
			'sort' => array (
				array ('is_int', '排序必须为数字'),
			),
		);
	}
}?>