<?php
/**
*
* 商品评价表修改Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:26:20Z robert zhao $
*/

namespace app\form\ProEvaluate;
use app\lib\form\BaseForm;

class ProEvaluateEdit extends BaseForm {
	
	protected $_id 	= null;

	protected $_orderno 	= null;

	protected $_businessid 	= null;

	protected $_productid 	= null;

	protected $_skuid 	= null;

	protected $_productname 	= null;

	protected $_productprice 	= null;

	protected $_scores 	= null;

	protected $_content 	= null;

	protected $_isanonymous 	= null;

	protected $_addtime 	= null;

	protected $_frommemberid 	= null;

	protected $_frommembername 	= null;

	protected $_headpic 	= null;

	protected $_state 	= null;

	protected $_remark 	= null;

	protected $_parentid 	= null;

	protected $_rootid 	= null;

	protected $_istop 	= null;

	protected $_desccredit 	= null;

	protected $_servicecredit 	= null;

	protected $_deliverycredit 	= null;

	protected $_evaluateauto 	= null;

			
	protected function getValidations() {
		return array (
			'id' => array (
				array ('not_empty', '非法操作'),
				array ('is_digits', '非法操作')
			),
			'orderno' => array (
				array ('not_empty', '订单号不能为空'),
				array ('max_length', 20, '订单号长度不能超过20'),
			),
			'businessid' => array (
				array ('is_int', '商家id必须为数字'),
			),
			'productid' => array (
				array ('is_int', '商品id必须为数字'),
			),
			'skuid' => array (
				array ('is_int', '商品sku的id必须为数字'),
			),
			'productname' => array (
				array ('max_length', 100, '商品名称长度不能超过100'),
			),
			'productprice' => array (
			),
			'scores' => array (
				array ('is_int', '评分必须为数字'),
			),
			'content' => array (
				array ('max_length', 255, '评价内容长度不能超过255'),
			),
			'isanonymous' => array (
				array ('is_int', '是否匿名必须为数字'),
			),
			'addtime' => array (
			),
			'frommemberid' => array (
				array ('is_int', '评价会员id必须为数字'),
			),
			'frommembername' => array (
				array ('max_length', 100, '评价会员名称长度不能超过100'),
			),
			'headpic' => array (
				array ('max_length', 100, '评价会员头像长度不能超过100'),
			),
			'state' => array (
				array ('is_int', '评价信息的状态 0为正常 1为禁止显示必须为数字'),
			),
			'remark' => array (
				array ('max_length', 255, '管理员对评价的处理备注长度不能超过255'),
			),
			'parentid' => array (
				array ('is_int', '针对回复的id必须为数字'),
			),
			'rootid' => array (
				array ('is_int', '根评价id必须为数字'),
			),
			'istop' => array (
				array ('is_int', '是否置顶必须为数字'),
			),
			'desccredit' => array (
				array ('is_int', '商品描述评分必须为数字'),
			),
			'servicecredit' => array (
				array ('is_int', '服务态度评分必须为数字'),
			),
			'deliverycredit' => array (
				array ('is_int', '发货速度评分必须为数字'),
			),
			'evaluateauto' => array (
				array ('is_int', '是否自动评价，0为用户评价，1为自动评价必须为数字'),
			),
		);
	}
}?>