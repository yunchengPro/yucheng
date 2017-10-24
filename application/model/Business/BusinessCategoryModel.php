<?php
// +----------------------------------------------------------------------
// |  [ 店铺分类模型 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017-2020 http://nnh.com All rights reserved.
// +----------------------------------------------------------------------
// | @Author ISir<673638498@qq.com>
// | @DateTime 2016-03-04
// +----------------------------------------------------------------------
namespace app\model\Business;

use app\lib\Db;

use app\lib\Img;

use app\lib\Model;

class BusinessCategoryModel{

	
	/**
	 * [getCateData 获取分类]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-04T11:11:18+0800
	 * @return   [type]                   [description]
	 */
	public static function getCateData($businessid){
		
		return $cateData =   Db::Model('BusBusinessCategory')->getCateData($businessid);
		
	}

	/**
	 * [returSelectTopCate 返回选择父级分类数据]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-14T17:09:21+0800
	 * @return   [type]                   [description]
	 */
	public static function returnSelectTopCate($businessid,$cid = ''){
        if(empty($cid)){
		  $cateData =   Db::Model('BusBusinessCategory')->getList(['parent_id'=>0,'businessid'=>$businessid,'is_delete'=>['<>',-1]],'id,category_name');
        }else{
            $cateData =   Db::Model('BusBusinessCategory')->getList(['parent_id'=>0,'businessid'=>$businessid,'id'=>['<>',$cid],'is_delete'=>['<>',-1]],'id,category_name');
        }
		
		$topCate = [];
		foreach ($cateData as $key => $value) {
			$topCate[$value['id']] = $value['category_name'];
		}
	
		return $topCate;
	}

	/**
	 * [formart_category 分类管理]
	 * @Author   ISir<673638498@qq.com>
	 * @DateTime 2017-03-14T19:30:23+0800
	 * @param    [type]                   $category [description]
	 * @return   [type]                             [description]
	 */
	public function formart_category($businessid){
	
		$cateData =   Db::Model('BusBusinessCategory')->getList(['businessid'=>$businessid,'is_delete'=>['<>',-1]],'*');

		return $treeCate = self::tree($cateData,'category_name');

	}


	  /**
     * 获得树状数据
     *
     * @param $data 数据
     * @param $title 字段名
     * @param string $fieldPri 主键id
     * @param string $fieldPid 父id
     *
     * @return array
     */
    public static function tree( $data, $title, $fieldPri = 'id', $fieldPid = 'parent_id' ) {
        if ( ! is_array( $data ) || empty( $data ) ) {
            return [ ];
        }
        $arr = self::channelList( $data, 0, '', $fieldPri, $fieldPid );
       
        foreach ( $arr as $k => $v ) {
            $str = "";
            if ( $v['_level'] > 2 ) {
                for ( $i = 1;$i < $v['_level'] - 1;$i ++ ) {
                    $str .= "│&nbsp;&nbsp;&nbsp;&nbsp;";
                }
            }
            if ( $v['_level'] != 1 ) {
                $t = $title ? $v[ $title ] : '';
                if ( isset( $arr[ $k + 1 ] ) && $arr[ $k + 1 ]['_level'] >= $arr[ $k ]['_level'] ) {
                    $arr[ $k ][ '_' . $title ] = $str . "├─ " . $v['_html'] . $t;
                } else {
                    $arr[ $k ][ '_' . $title ] = $str . "└─ " . $v['_html'] . $t;
                }
            } else {
                $arr[ $k ][ '_' . $title ] = $v[ $title ];
            }
        }
        //设置主键为$fieldPri
        $data = [ ];
        foreach ( $arr as $d ) {
            //            $data[$d[$fieldPri]] = $d;
            $data[] = $d;
        }

        return $data;
    }

    /**
     * 获得所有子栏目
     *
     * @param $data 栏目数据
     * @param int $pid 操作的栏目
     * @param string $html 栏目名前字符
     * @param string $fieldPri 表主键
     * @param string $fieldPid 父id
     * @param int $level 等级
     *
     * @return array
     */
    public static function channelList( $data, $pid = 0, $html = "&nbsp;", $fieldPri = 'id', $fieldPid = 'parent_id', $level = 1 ) {
        $data = self::_channelList( $data, $pid, $html, $fieldPri, $fieldPid, $level );
        if ( empty( $data ) ) {
            return $data;
        }
        foreach ( $data as $n => $m ) {
            if ( $m['_level'] == 1 ) {
                continue;
            }
            $data[ $n ]['_first'] = FALSE;
            $data[ $n ]['_end']   = FALSE;
            if ( ! isset( $data[ $n - 1 ] ) || $data[ $n - 1 ]['_level'] != $m['_level'] ) {
                $data[ $n ]['_first'] = TRUE;
            }
            if ( isset( $data[ $n + 1 ] ) && $data[ $n ]['_level'] > $data[ $n + 1 ]['_level'] ) {
                $data[ $n ]['_end'] = TRUE;
            }
        }
        //更新key为栏目主键
        $category = [ ];
        foreach ( $data as $d ) {
            $category[ $d[ $fieldPri ] ] = $d;
        }

        return $category;
    }

    //只供channelList方法使用
    private static function  _channelList( $data, $pid = 0, $html = "&nbsp;", $fieldPri = 'cid', $fieldPid = 'pid', $level = 1 ) {
        if ( empty( $data ) ) {
            return [ ];
        }
        $arr = [ ];
        foreach ( $data as $v ) {
            $id = $v[ $fieldPri ];
            if ( $v[ $fieldPid ] == $pid ) {
                $v['_level'] = $level;
                $v['_html']  = str_repeat( $html, $level - 1 );
                array_push( $arr, $v );
                $tmp = self::channelList( $data, $id, $html, $fieldPri, $fieldPid, $level + 1 );
                $arr = array_merge( $arr, $tmp );
            }
        }

        return $arr;
    }


    //获取所属分类的子分类
    public function getChildCategoryStr($businessid,$categoryid){

        $list = Model::ins("BusBusinessCategory")->getList(["businessid"=>$businessid,"parent_id"=>$categoryid],"id");

        $idstr = '';
        foreach($list as $k=>$v){
          $idstr.=$v['id'].",";
        }

        $idstr = $idstr!=''?substr($idstr,0,-1):'';

        return $idstr;
    }
}
