<?php
/**
*
* 商品详细信息修改Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 14:54:16Z robert zhao $
*/

namespace app\form\ProProductInfo;
use app\lib\form\BaseForm;

class ProProductInfoEdit extends BaseForm {
	
	protected $_id 	= null;

	protected $_description 	= null;

	protected $_filterkeyword 	= null;

	protected $_evaluatecount 	= null;

	protected $_salecount 	= null;

			
	protected function getValidations() {
		return array (
			'id' => array (
				array ('not_empty', '非法操作'),
				array ('is_digits', '非法操作')
			),
			'description' => array (
				array ('not_empty', '商品描述不能为空'),
			),
			'filterkeyword' => array (
				array ('max_length', 255, '关键字长度不能超过255'),
			),
			'evaluatecount' => array (
				array ('is_int', '评价次数必须为数字'),
			),
			'salecount' => array (
				array ('is_int', '销售次数必须为数字'),
			),
		);
	}
}?>