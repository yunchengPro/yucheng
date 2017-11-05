<?php
/**
*
* 产品类型分类关系表添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 21:34:19Z robert zhao $
*/
namespace app\form\ProModuleCategoryRelation;
use app\lib\form\BaseForm;

class ProModuleCategoryRelationAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_moduleId 	= null;

	protected $_categoryId 	= null;

	protected $_sort 	= null;

			
	protected function getValidations() {
		return array (
			'moduleId' => array (
				array ('is_digits', '类型id pro_module主键必须为数字'),
			),
			'categoryId' => array (
				array ('is_digits', '分类id pro_category主键必须为数字'),
			),
			'sort' => array (
				array ('is_digits', '排序必须为数字'),
			),
		);
	}
}?>