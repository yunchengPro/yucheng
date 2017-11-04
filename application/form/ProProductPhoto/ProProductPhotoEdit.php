<?php
/**
*
* 商品关联图片修改Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 14:53:23Z robert zhao $
*/

namespace app\form\ProProductPhoto;
use app\lib\form\BaseForm;

class ProProductPhotoEdit extends BaseForm {
	
	protected $_id 	= null;

	protected $_productid 	= null;

	protected $_productpic 	= null;

	protected $_istop 	= null;

	protected $_sort 	= null;

	protected $_addtime 	= null;

			
	protected function getValidations() {
		return array (
			'id' => array (
				array ('not_empty', '非法操作'),
				array ('is_digits', '非法操作')
			),
			'productid' => array (
				array ('not_empty', '商品id不能为空'),
				array ('is_int', '商品id必须为数字'),
			),
			'productpic' => array (
				array ('not_empty', '商品图片不能为空'),
				array ('max_length', 100, '商品图片长度不能超过100'),
			),
			'istop' => array (
				array ('not_empty', '商品首图1为首图不能为空'),
				array ('is_int', '商品首图1为首图必须为数字'),
			),
			'sort' => array (
				array ('not_empty', '排序不能为空'),
				array ('is_int', '排序必须为数字'),
			),
			'addtime' => array (
				array ('not_empty', '添加时间不能为空'),
			),
		);
	}
}?>