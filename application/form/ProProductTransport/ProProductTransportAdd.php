<?php
/**
*
* 商品运费信息表添加Form
* 
*
* @copyright  Copyright (c) 2009-2011 雲牛匯(深圳)科技有限公司
* @version    $Id: 2017-03-03 15:04:56Z robert zhao $
*/
namespace app\form\ProProductTransport;
use app\lib\form\BaseForm;

class ProProductTransportAdd extends BaseForm {
	
	protected $_id 	= null;

	protected $_transportid 	= null;

	protected $_transporttitle 	= null;

	protected $_freight 	= null;

			
	protected function getValidations() {
		return array (
			'transportid' => array (
				array ('not_empty', '运费模板id不能为空'),
				array ('is_digits', '运费模板id必须为数字'),
			),
			'transporttitle' => array (
				array ('not_empty', '运费模版名称不能为空'),
				array ('max_length', 50, '运费模版名称长度不能超过50'),
			),
			'freight' => array (
				array ('not_empty', '固定运费不能为空'),
				array ('is_digits', '固定运费必须为数字'),
			),
		);
	}
}?>