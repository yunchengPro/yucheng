<?php
/**
*
* 商品所有规格信息表添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:03:36Z robert zhao $
*/
namespace app\form\ProProductSpecValue;
use app\lib\form\BaseForm;

class ProProductSpecValueAdd extends BaseForm {
	
	protected $_productid 	= null;

	protected $_specName 	= null;

	protected $_specVlaue 	= null;

			
	protected function getValidations() {
		return array (
			'productid' => array (
				array ('not_empty', '商品id不能为空'),
				array ('is_digits', '商品id必须为数字'),
			),
			'spec_name' => array (
				array ('not_empty', '规格名称json组合不能为空'),
				array ('max_length', 255, '规格名称json组合长度不能超过255'),
			),
			'spec_vlaue' => array (
				array ('not_empty', '规格值json，规则组合数组不能为空'),
			),
		);
	}
}?>