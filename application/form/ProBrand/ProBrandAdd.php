<?php
/**
*
* 品牌表添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-15 10:02:27Z robert zhao $
*/
namespace app\form\ProBrand;
use app\lib\form\BaseForm;

class ProBrandAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_brandname 	= null;

	protected $_brandlogo 	= null;

	protected $_remark 	= null;

	protected $_country 	= null;

	protected $_company 	= null;

	protected $_sort 	= null;

	protected $_isdelete 	= null;

			
	protected function getValidations() {
		return array (
			'brandname' => array (
				array ('max_length', 100, '品牌名称长度不能超过100'),
			),
			'brandlogo' => array (
				array ('max_length', 100, '品牌logo长度不能超过100'),
			),
			'remark' => array (
				array ('max_length', 255, '描述长度不能超过255'),
			),
			// 'country' => array (
			// 	array ('is_digits', '国家编号采用标准的国际编号必须为数字'),
			// ),
			'company' => array (
				array ('max_length', 255, '品牌所属公司长度不能超过255'),
			),
			'sort' => array (
				array ('is_digits', '排序必须为数字'),
			),
			'isdelete' => array (
				array ('is_digits', '是否删除必须为数字'),
			),
		);
	}
}?>