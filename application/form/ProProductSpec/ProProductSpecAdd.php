<?php
/**
*
* 商品对应规格添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 14:56:50Z robert zhao $
*/
namespace app\form\ProProductSpec;
use app\lib\form\BaseForm;

class ProProductSpecAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_productid 	= null;

	protected $_productname 	= null;

	protected $_prouctprice 	= null;

	protected $_bullamount 	= null;

	protected $_productstorage 	= null;

	protected $_weight 	= null;

	protected $_weightGross 	= null;

	protected $_volume 	= null;

	protected $_productspec 	= null;

	protected $_spec 	= null;

	protected $_productimage 	= null;

	protected $_sku 	= null;

			
	protected function getValidations() {
		return array (
			'productid' => array (
				array ('not_empty', '商品id不能为空'),
				array ('is_digits', '商品id必须为数字'),
			),
			'productname' => array (
				array ('not_empty', '商品名称不能为空'),
				array ('max_length', 255, '商品名称长度不能超过255'),
			),
			// 'prouctprice' => array (
			// 	array ('is_digits', '商品价格必须为数字'),
			// ),
			// 'bullamount' => array (
			// 	array ('is_digits', '商品牛币积分必须为数字'),
			// ),
			'productstorage' => array (
				array ('not_empty', '商品库存不能为空'),
				array ('is_digits', '商品库存必须为数字'),
			),
			'weight' => array (
			),
			'weight_gross' => array (
			),
			'volume' => array (
				array ('max_length', 100, '体积长*宽*高长度不能超过100'),
			),
			'productspec' => array (
				array ('not_empty', '商品规格序列化不能为空'),
				array ('max_length', 255, '商品规格序列化长度不能超过255'),
			),
			'spec' => array (
				array ('not_empty', '存储格式化的规格和规格值不能为空'),
			),
			// 'productimage' => array (
			// 	array ('not_empty', '商品对应sku图片不能为空'),
			// 	array ('max_length', 100, '商品对应sku图片长度不能超过100'),
			// ),
			'sku' => array (
				array ('max_length', 50, '商品对应sku码长度不能超过50'),
			),
		);
	}
}?>