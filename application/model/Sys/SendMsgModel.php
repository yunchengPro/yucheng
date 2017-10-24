<?php
namespace app\model\Sys;
use app\lib\Model;

class SendMsgModel {
   
    /**
     * [sendOneSystemMsg 单条发送系统消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-13T14:58:44+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function sendOneSystemMsg(){
       
        //$userid =  $param['userid'];
        $userData = Model::ins('CusRongcloud')->getList(['customerid'=>['>',0]],'userid,customerid','id desc');
        //$userData = Model::ins('CusRongcloud')->getList(['customerid'=>['in','4,28']],'userid,customerid','id desc');//
       
        //$userData = Model::ins('CusRongcloud')->getList(['customerid'=>28],'userid,customerid','id desc');
        foreach ($userData as $key => $value) {
           
            // if($key == 0)
            //     $value['userid'] = '5e895b1e94ca3b1833fcfddfedfd57be';
            $tmp_data = Model::ins("SysMsgsendTmp")->getRow(['customerid'=>$value['customerid']],'id');
            if(!empty($value['userid']) && empty($tmp_data)){ 
                $param['userid'] = $value['userid'];
                $result = Model::new("Msg.SendMsg")->SendOneSysMsg($param);

                $insert = [
                    'customerid'=> $value['customerid'],
                    'addtime' => date('Y-m-d H:i:s')
                ];
               
               Model::ins("SysMsgsendTmp")->insert($insert);
            }
        }
       
        return true;
    }


    /**
     * [sendOneSystemMsg 单条发送系统消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-13T14:58:44+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function sendTwoSystemMsg(){
       
        //$userid =  $param['userid'];
        $userData = Model::ins('CusRongcloud')->getList(['customerid'=>['>',0]],'userid,customerid','id desc');
        //$userData = Model::ins('CusRongcloud')->getList(['customerid'=>['in','288,283']],'userid,customerid','id desc');//
       
        //$userData = Model::ins('CusRongcloud')->getList(['customerid'=>28],'userid,customerid','id desc');
        foreach ($userData as $key => $value) {
           
            // if($key == 0)
            //     $value['userid'] = '5e895b1e94ca3b1833fcfddfedfd57be';
            $tmp_data = Model::ins("SysMsgsendTmp")->getRow(['customerid'=>$value['customerid']],'id');
            if(!empty($value['userid']) && empty($tmp_data)){ 
                $param['userid'] = $value['userid'];
                $result = Model::new("Msg.SendMsg")->SendTwoSysMsg($param);

                $insert = [
                    'customerid'=> $value['customerid'],
                    'addtime' => date('Y-m-d H:i:s')
                ];
               
               Model::ins("SysMsgsendTmp")->insert($insert);
            }
        }
       
        return true;
    }

    /**
     * [sendOneSystemMsg 单条发送系统消息]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-13T14:58:44+0800
     * @param    [type]                   $param [description]
     * @return   [type]                          [description]
     */
    public function sendTestSystemMsg(){
       
        //$userid =  $param['userid'];
        //$userData = Model::ins('CusRongcloud')->getList(['customerid'=>['>',0]],'userid,customerid','id desc');
        $userData = Model::ins('CusRongcloud')->getList(['customerid'=>['in','288,283']],'userid,customerid','id desc');//
       
        //$userData = Model::ins('CusRongcloud')->getList(['customerid'=>28],'userid,customerid','id desc');
        foreach ($userData as $key => $value) {
           
            // if($key == 0)
            //     $value['userid'] = '5e895b1e94ca3b1833fcfddfedfd57be';
            $tmp_data = Model::ins("SysMsgsendTmp")->getRow(['customerid'=>$value['customerid']],'id');
            if(!empty($value['userid']) && empty($tmp_data)){ 
                $param['userid'] = $value['userid'];
                $result = Model::new("Msg.SendMsg")->SendTwoSysMsg($param);

                $insert = [
                    'customerid'=> $value['customerid'],
                    'addtime' => date('Y-m-d H:i:s')
                ];
               
               Model::ins("SysMsgsendTmp")->insert($insert);
            }
        }
       
        return true;
    }

}
?>