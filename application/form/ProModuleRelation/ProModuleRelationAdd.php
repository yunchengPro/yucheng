<?php
/**
*
* 产品类型规格属性关系表添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-14 21:36:59Z robert zhao $
*/
namespace app\form\ProModuleRelation;
use app\lib\form\BaseForm;

class ProModuleRelationAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_moduleId 	= null;

	protected $_type 	= null;

	protected $_objId 	= null;

	protected $_sort 	= null;

			
	protected function getValidations() {
		return array (
			'moduleId' => array (
				array ('is_digits', '类型id pro_module主键必须为数字'),
			),
			'type' => array (
				array ('is_digits', '关系类型1类型与规格关系2类型与属性关系必须为数字'),
			),
			'objId' => array (
				array ('is_digits', '关系主键必须为数字'),
			),
			'sort' => array (
				array ('is_digits', '排序必须为数字'),
			),
		);
	}
}?>