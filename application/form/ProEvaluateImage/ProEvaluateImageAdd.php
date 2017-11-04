<?php
/**
*
* 商品评价的图片添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:27:22Z robert zhao $
*/
namespace app\form\ProEvaluateImage;
use app\lib\form\BaseForm;

class ProEvaluateImageAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_evaluateId 	= null;

	protected $_thumb 	= null;

	protected $_addtime 	= null;

			
	protected function getValidations() {
		return array (
			'evaluateId' => array (
				array ('not_empty', '对应的评价id不能为空'),
				array ('is_digits', '对应的评价id必须为数字'),
			),
			'thumb' => array (
				array ('not_empty', '评价图片不能为空'),
				array ('max_length', 100, '评价图片长度不能超过100'),
			),
			'addtime' => array (
				array ('not_empty', '添加时间不能为空'),
			),
		);
	}
}?>