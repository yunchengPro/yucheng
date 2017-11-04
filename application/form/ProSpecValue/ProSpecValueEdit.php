<?php
/**
*
* 商品规格值表修改Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:09:33Z robert zhao $
*/

namespace app\form\ProSpecValue;
use app\lib\form\BaseForm;

class ProSpecValueEdit extends BaseForm {
	
	protected $_id 	= null;

	protected $_specValueName 	= null;

	protected $_specId 	= null;

	protected $_categoryId 	= null;

	protected $_storeId 	= null;

	protected $_specValueColor 	= null;

	protected $_specValueSort 	= null;

	protected $_isdelete 	= null;

			
	protected function getValidations() {
		return array (
			'id' => array (
				array ('not_empty', '非法操作'),
				array ('is_digits', '非法操作')
			),
			'specValueName' => array (
				array ('not_empty', '规格值名称不能为空'),
				array ('max_length', 100, '规格值名称长度不能超过100'),
			),
			'specId' => array (
				array ('not_empty', '所属规格id不能为空'),
				array ('is_int', '所属规格id必须为数字'),
			),
			'categoryId' => array (
				array ('not_empty', '分类id不能为空'),
				array ('is_int', '分类id必须为数字'),
			),
			'storeId' => array (
				array ('not_empty', '店铺id不能为空'),
				array ('is_int', '店铺id必须为数字'),
			),
			'specValueColor' => array (
				array ('not_empty', '规格颜色不能为空'),
				array ('max_length', 10, '规格颜色长度不能超过10'),
			),
			'specValueSort' => array (
				array ('is_int', '排序必须为数字'),
			),
			'isdelete' => array (
				array ('is_int', '是否删除必须为数字'),
			),
		);
	}
}?>