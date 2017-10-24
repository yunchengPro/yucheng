<?php
/**
 * 商家店铺
 */
namespace app\model;

use app\lib\Db;

use app\lib\Img;

class BusinessModel
{   

    /**
     * 获取店铺基本信息：图片+店铺名称
     * @Author   zhuangqm
     * @DateTime 2017-02-28T17:53:36+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
	public static function getBaseInfo($id){
        
        $row = Db::Table("BusBusiness")->getRow(["id"=>$id],"businessname,businesslogo");

        $row['businesslogo'] = Img::url($row['businesslogo']);

        return $row;
    }

    
}
