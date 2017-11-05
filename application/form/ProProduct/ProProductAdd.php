<?php
/**
*
* 商品关联图片添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 14:52:14Z robert zhao $
*/
namespace app\form\ProProduct;
use app\lib\form\BaseForm;

class ProProductAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_spu 	= null;

	protected $_businessid 	= null;

	protected $_productname 	= null;

	protected $_categoryid 	= null;

	protected $_categoryname 	= null;

	protected $_addtime 	= null;

	protected $_enable 	= null;

	protected $_enabletime 	= null;

	protected $_thumb 	= null;

	protected $_productunit 	= null;

	protected $_weight 	= null;

	protected $_weightGross 	= null;

	protected $_volume 	= null;

	protected $_stateremark 	= null;

	protected $_prouctprice 	= null;

	protected $_bullamount 	= null;

			
	protected function getValidations() {
		return array (
			// 'spu' => array (
			// 	array ('not_empty', '商品spu编码不能为空'),
			// 	array ('max_length', 30, '商品spu编码长度不能超过30'),
			// ),
			'businessid' => array (
				array ('not_empty', '所属商家不能为空'),
				array ('is_integer', '所属商家必须为数字'),
			),
			'productname' => array (
				array ('not_empty', '商品名称不能为空'),
				array ('max_length', 145, '商品名称长度不能超过145'),
			),
			// 'categoryid' => array (
			// 	array ('not_empty', '商品所属分类不能为空'),
			// 	array ('is_integer', '商品所属分类必须为数字'),
			// ),
		
			// 'addtime' => array (
			// 	array ('not_empty', '添加时间不能为空'),
			// ),
			'enable' => array (
				//array ('not_empty', '是否上架不能为空'),
				//array ('is_integer', '是否上架必须为数字'),
			),
			// 'enabletime' => array (
			// 	array ('not_empty', '下架上架时间不能为空'),
			// ),
			'thumb' => array (
				array ('not_empty', '商品主图不能为空'),
				//array ('max_length', 100, '商品主图长度不能超过100'),
			),
			// 'productunit' => array (
			// 	array ('not_empty', '单位不能为空'),
			// 	array ('max_length', 50, '单位长度不能超过50'),
			// ),
			'weight' => array (
				array ('not_empty', '净重不能为空'),
				array ('is_float', '净重必须为数字'),
			),
			'weight_gross' => array (
				array ('not_empty', '毛重不能为空'),
				array ('is_float', '毛重必须为数字'),
			),
			'volume' => array (
				array ('not_empty', '体积长*宽*高不能为空'),
				array ('is_float', '体积必须为数字'),
			),
			'stateremark' => array (
				array ('max_length', 255, '违规下架理由长度不能超过255'),
			),
			// 'prouctprice' => array (
			// 	array ('is_integer', '商品价格必须为数字'),
			// ),
			'bullamount' => array (
				array ('is_integer', '商品牛币积分必须为数字'),
			),
		);
	}
}?>