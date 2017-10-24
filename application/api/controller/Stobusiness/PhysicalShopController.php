<?php
    namespace app\api\controller\StoBusiness;
    use app\api\ActionController;
    use app\model\StoBusiness\PhysicalShopModel;
    use app\model\User\UserModel;
    use app\model\Business\BusinessModel;
    use app\model\StoPayFlowModel;
    use app\lib\Model;
    use think\Config;
    use app\model\StoBusiness\StobusinessModel;
    use app\lib\Img;
    use app\model\User\TokenModel;
    use app\model\User\CollectionModel;
    use app\model\Sys\CommonModel;
    use app\model\CusCustomerModel;
                                
    class PhysicalShopController extends ActionController {
        
        const sendType = 'sto_store_';
        /**
         * 初始化父级构造函数
        */
        public function __construct() {
            parent::__construct();
        }
        
        /**
         * @Function :实体店详情页
         * @Author  xurui
         * @DateTime 2017/03/09 pm
         * @param   shop_id  int  店铺id
         * @return  json
         */
        public function shopDetailsAction() { 
            $shop_id = $this->params['shop_id']; //商铺id
            $mtoken = $this->params['mtoken'];

          
            $noWh = 0;
           
            if($this->Version("1.0.2") || $this->dev_type=='A' ){
                $noWh = 1;
            }
            if($this->Version("1.0.4")){
                $sqRecWh =  1;
            }
            $newVersion =0;
            if($this->Version("2.2.0")){
                $newVersion = 1;
            }
            if (!empty($shop_id) && is_numeric($shop_id)) {
                $shopDetails = PhysicalShopModel::getShopDetails($shop_id,$noWh,$sqRecWh,$newVersion); //获得店铺详情
             
                if (!empty($shopDetails) && is_array($shopDetails)) {
                    $check = false;
                    if(!empty($mtoken)){
                        $tokenModel = new TokenModel();
                        $userId = $tokenModel->getTokenId($mtoken);
                        
                        $params = [
                            'type' => 3,
                            'obj_id' => $shop_id,
                            'userid' => $userId['id']
                        ];
                       
                        $check = CollectionModel::checkCollectcount($params);
                       
                    }

                    if($check){
                        $shopDetails['iscollect'] = 1;
                    }else{
                        $shopDetails['iscollect'] = -1;
                    }
                   
                    return $this->json(200,$shopDetails,'获取店铺详情成功');
                } else {
                    return $this-> json ('8001');
                }
            } else {
                return $this->json('404');
            }
        }
        
        /**
         * @Function :用户评论列表
         * @Author  xurui
         * @DateTime 2017/03/09 pm
         * @Param   shop_id  int     店铺id
         * @Return  array
         */
        public function commentListAction() {
            $shop_id = $this->params['shop_id'] ?: ''; //店铺id
            if (!empty($shop_id) && is_numeric($shop_id)) {
                $commentList = PhysicalShopModel::getEvaluateList($shop_id); //评论列表
                if (!empty($commentList) && is_array($commentList)) {
                    return $this->json(200,$commentList,'获取店铺用户评论列表成功');
                } else {
                    return $this->json('8007');
                }
            } else {
                return $this->json('404');
            }
        }
        
        /**
         * @Function :用户投诉
         * @Author  xurui
         * @DateTime 2017/03/09 pm
         */
        public function writeProposalAction() {

            $proposalParam = array(
                'businessid' => intval($this->params['shop_id']) ?: '',//店铺id
                'customerid' => intval($this->userid) ?: '',//客户id
                'content' => trim($this->params['content']) ?: '',//投诉内容,并且最多100个字符
                'addtime' => date('Y-m-d H:i:s'), //添加时间
            );
            $Length = getStringLength($proposalParam['content']);
            if($Length >= 200)
                return $this->json(5012);
            if (!empty($proposalParam['businessid']) && !empty($proposalParam['customerid'])) {
                //检查商家、买家是否合法
                $checkCustomer = UserModel::checkCustomer($proposalParam['customerid']); //检查用户
                $checkBusiness = StoBusinessModel::checkStoBusiness($proposalParam['businessid']); //检查商家
                if (!empty($checkCustomer) && !empty($checkBusiness)) {
                    $writeProposal = PhysicalShopModel::writeProposal($proposalParam); //写投诉
                    if (!empty($writeProposal)) {
                        return $this->json(200,$writeProposal,'用户投诉成功');
                    } else{
                        return $this->json('8004');
                    }
                } else{
                   if (empty($checkCustomer)) return $this->json('8009'); //非法用户
                   if (empty($checkBusiness)) return $this->json('8010'); //非法商家
                }
            } else {
                return  $this->json('404');
            }
        }
        
        /**
         * @Function :用户点评
         * @Author  xurui
         * @DateTime 2017/03/09 pm
         */
        public function writeCommentAction() {

              
            if(!empty($this->params['frommemberid']))
                 $this->userid = $this->params['frommemberid'];

           
            $userData = Model::ins('CusCustomerInfo')->getRow(['id'=>$this->userid],'nickname,headerpic');
            $mobile  = Model::ins('CusCustomer')->getRow(['id'=>$this->userid],'mobile')['mobile'];
            $type = 1;
            $ordernoStr =  substr($this->params['order_no'],0,6);
            if($ordernoStr=='NNHOTO'){
                $type = 2;
            }
            //评论信息
            $evaluateParam = array(
                'orderno' => trim($this->params['order_no']),//订单号
                'businessid' => !empty($this->params['shop_id']) ? intval($this->params['shop_id']) : 0,//店铺id
                'scores'    => !empty($this->params['scores']) ? floatval($this->params['scores']) : '0', //用户评分
                'headpic' =>  $userData['headerpic'],
                'isanonymous'    => !empty($this->params['is_anonymous']) ? intval($this->params['is_anonymous']) : 1, //是否匿名评价
                'frommemberid' => $this->userid ,//用户id
                'frommembername' => empty($userData['nickname']) ? $mobile : $userData['nickname'],//用户名称
                'content' => trim($this->params['content']) ?: '',//评价内容 字符内容为：1~100
                'addtime' => date('Y-m-d H:i:s'), //添加时间
                'type' => $type
            );
              
            $Length = getStringLength($evaluateParam['content']);
            if($Length >= 200)
                return $this->json(5012);

            if (!empty($evaluateParam['businessid']) && !empty($evaluateParam['frommemberid']) && !empty($evaluateParam['orderno'])) {
                
                $check_params = array(
                    'businessid'=>$evaluateParam['businessid'],
                    'customerid'=>$evaluateParam['frommemberid'],
                    'pay_code'=>$evaluateParam['orderno'],
                );

                $check_res = self::checkOrder($check_params); //检查订单、商家、买家交集是否合法 ,评论基于订单的 所以最佳的方式是：基于订单、商家、买家
                if (!empty($check_res)) {
                    $comment_id = PhysicalShopModel::writeComment($evaluateParam); //写评论
                    if (!empty($comment_id) && is_numeric($comment_id)) {//评论写入成功
                        
                        //更新外卖订单评价状态
                        if($type == 2){
                            
                            Model::ins('StoOrder')->update(['orderstatus'=>5,'evaluate'=>1,'finish_time'=>date('Y-m-d H:i:s')],['orderno'=>$this->params['order_no']]);
                        }

                        /*插入图片信息*/
                        $thumb_arr = $this->params['thumb'];//多张地址，以json数据输出
                        if (!empty($thumb_arr)) {
                            $thum_arr = explode(',',$thumb_arr); //转换为数组
                           
                            $datetime = date('Y-m-d H:i:s',time());
                            foreach ($thum_arr as $key => $value) {
                                if(!empty($value)){
                                    $temp = array();
                                    $temp['evaluate_id'] =$comment_id;
                                    $temp['thumb'] =$value;//图片地址
                                    $temp['addtime'] =$datetime;
                                }
                                if (!empty($temp)) PhysicalShopModel::saveImages($temp);//循环插入多张评论图片
                                unset($temp);
                            }
                        }
                        /*更新店铺评分*/
                        if (!empty($evaluateParam['scores']) && is_numeric($evaluateParam['scores'])) PhysicalShopModel::updateShopScores($evaluateParam['businessid']);//更新店铺评分
                        //json返回
                        return $this->json(200);
                    } else {
                        return $this->json('8008');
                    }
                }else{
                    return  $this->json('404');
                }
            } else {
             
                return  $this->json('404');
            }
        }
        
        /**
         * [stoAlbumAction 获取商家相册]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T10:26:22+0800
         * @return   [type]                   [description]
         */
        public  function stoAlbumAction(){

            if(!empty($this->params['businessid'])){
               
                $data = PhysicalShopModel::stoAlbum($this->params);
               
                return  $this->json('200',$data);
               
            }else{
                return $this->json('404');
            }

        }

         /**
         * [addStoAlbumAction description]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T13:57:32+0800
         */
        public function  addStoAlbumAction(){

            
            //$stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid],'id,ischeck');
            
            $businessid = $this->params['businessid'];
            if(empty($businessid)){
                $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid],'id,ischeck');
            }else{

                $cusData = Model::ins('CusRelation')->getList(['role'=>5,'parentid'=>$this->userid,'parentrole'=>$this->params['roleid']],'customerid,role as recoRoleType,parentrole as selfRoleType');

                if(!empty($cusData)){
                    $customerids = '';
                    foreach ($cusData as $key => $value) {
                       $customerids.= $value['customerid'] .',';
                    }
                    //var_dump($cusData);
                    $busList = Model::ins('StoBusiness')->getList(['customerid'=>['in',rtrim($customerids,',')]],'id');
                }

                //var_dump($busList);
                $bus_arr = []; 
                foreach ($busList as $key => $value) {
                     $bus_arr[] = $value['id'];
                }
                //var_dump($bus_arr);

                if(!in_array($businessid,$bus_arr))
                    return  $this->json(1001);

                $stoBusData = Model::ins('StoBusiness')->getRow(['id'=>$businessid],'id,ischeck');
                
            }


            if(!empty($stoBusData)){

                if(!empty($stoBusData))
                    $this->params['businessid'] = $stoBusData['id'];
                if(empty($this->params['thumb']))
                    return $this->json(404);
                $data = PhysicalShopModel::addStoAlbum($this->params);

                if($data)
                    return  $this->json('200',$data);
            }else{
                return $this->json('60008');
            }

        }

        /**
         * [delStoAlbumAction 删除相册]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T14:17:31+0800
         * @return   [type]                   [description]
         */
        public function delStoAlbumAction(){

            //$stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1
            
            $businessid = $this->params['businessid'];
            if(empty($businessid)){
                $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid],'id,ischeck');
            }else{

                $cusData = Model::ins('CusRelation')->getList(['role'=>5,'parentid'=>$this->userid,'parentrole'=>$this->params['roleid']],'customerid,role as recoRoleType,parentrole as selfRoleType');

                if(!empty($cusData)){
                    $customerids = '';
                    foreach ($cusData as $key => $value) {
                       $customerids.= $value['customerid'] .',';
                    }
                    //var_dump($cusData);
                    $busList = Model::ins('StoBusiness')->getList(['customerid'=>['in',rtrim($customerids,',')]],'id');
                }

                //var_dump($busList);
                $bus_arr = []; 
                foreach ($busList as $key => $value) {
                     $bus_arr[] = $value['id'];
                }
                //var_dump($bus_arr);

                if(!in_array($businessid,$bus_arr))
                    return  $this->json(1001);

                $stoBusData = Model::ins('StoBusiness')->getRow(['id'=>$businessid],'id,ischeck');
                
            }

            if(!empty($stoBusData)){

                if(empty($this->params['id']))
                       return $this->json('404');

                $this->params['businessid'] = $stoBusData['id'];
                
                $data = PhysicalShopModel::delStoAlbum($this->params);

                if($data){
                    return  $this->json('200',$data);
                }else{
                    return $this->json('1001');
                }
            }else{
                return $this->json('60008');
            }
        }

        /**
         * [stoAlbumAction 获取商家轮播]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T10:26:22+0800
         * @return   [type]                   [description]
         */
        public  function stoImagesAction(){

            if(!empty($this->params['businessid'])){
               
                $data = PhysicalShopModel::stoImages($this->params);
                
                return  $this->json('200',$data);
               
            }else{
                return $this->json('404');
            }

        }

        /**
         * [addStoImagesAction 添加实体店轮播图]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-24T17:08:29+0800
         */
        public function addStoImagesAction(){

            $businessid = $this->params['businessid'];
            if(empty($businessid)){
                $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid],'id,ischeck');
            }else{

                $cusData = Model::ins('CusRelation')->getList(['role'=>5,'parentid'=>$this->userid,'parentrole'=>$this->params['roleid']],'customerid,role as recoRoleType,parentrole as selfRoleType');

                if(!empty($cusData)){
                    $customerids = '';
                    foreach ($cusData as $key => $value) {
                       $customerids.= $value['customerid'] .',';
                    }
                    //var_dump($cusData);
                    $busList = Model::ins('StoBusiness')->getList(['customerid'=>['in',rtrim($customerids,',')]],'id');
                }

                //var_dump($busList);
                $bus_arr = []; 
                foreach ($busList as $key => $value) {
                     $bus_arr[] = $value['id'];
                }
                //var_dump($bus_arr);

                if(!in_array($businessid,$bus_arr))
                    return  $this->json(1001);

                $stoBusData = Model::ins('StoBusiness')->getRow(['id'=>$businessid],'id,ischeck');
                
            }
            

            if(!empty($stoBusData)){

                if(!empty($stoBusData))
                    $this->params['businessid'] = $stoBusData['id'];
                
                if(empty($this->params['thumb']))
                    return $this->json(404);
                
                $data = PhysicalShopModel::addStoImages($this->params);

                if($data)
                    return  $this->json('200',$data);
            }else{
                return $this->json('60008');
            }
        }

        /**
         * [delStoImagesAction 删除轮播图]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-24T17:26:40+0800
         * @return   [type]                   [description]
         */
        public function delStoImagesAction(){
            
            // $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1
            $businessid = $this->params['businessid'];
            if(empty($businessid)){
                $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid],'id,ischeck');
            }else{

                $cusData = Model::ins('CusRelation')->getList(['role'=>5,'parentid'=>$this->userid,'parentrole'=>$this->params['roleid']],'customerid,role as recoRoleType,parentrole as selfRoleType');

                if(!empty($cusData)){
                    $customerids = '';
                    foreach ($cusData as $key => $value) {
                       $customerids.= $value['customerid'] .',';
                    }
                    //var_dump($cusData);
                    $busList = Model::ins('StoBusiness')->getList(['customerid'=>['in',rtrim($customerids,',')]],'id');
                }

                //var_dump($busList);
                $bus_arr = []; 
                foreach ($busList as $key => $value) {
                     $bus_arr[] = $value['id'];
                }
                //var_dump($bus_arr);

                if(!in_array($businessid,$bus_arr))
                    return  $this->json(1001);

                $stoBusData = Model::ins('StoBusiness')->getRow(['id'=>$businessid],'id,ischeck');
                
            }

            if(!empty($stoBusData)){

                if(empty($this->params['id']))
                       return $this->json('404');

                $this->params['businessid'] = $stoBusData['id'];
                
                $data = PhysicalShopModel::delStoImages($this->params);

                if($data){
                    return  $this->json('200',$data);
                }else{
                    return $this->json('1001');
                }
            }else{
                return $this->json('60008');
            }
        }

        /**
         * [stoBusinessLogoAction 获取店铺主图]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-24T16:19:20+0800
         * @return   [type]                   [description]
         */
        public function stoBusinessLogoAction(){

            $businessid = $this->params['businessid'];

            if(empty($businessid)){
                $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid],'id,ischeck');
            }else{

               

                $cusData = Model::ins('CusRelation')->getList(['role'=>5,'parentid'=>$this->userid,'parentrole'=>$this->params['roleid']],'customerid,role as recoRoleType,parentrole as selfRoleType');

                if(!empty($cusData)){
                    $customerids = '';
                    foreach ($cusData as $key => $value) {
                       $customerids.= $value['customerid'] .',';
                    }
                    //var_dump($cusData);
                    $busList = Model::ins('StoBusiness')->getList(['customerid'=>['in',rtrim($customerids,',')]],'id');
                }

                //var_dump($busList);
                $bus_arr = []; 
                foreach ($busList as $key => $value) {
                     $bus_arr[] = $value['id'];
                }
                //var_dump($bus_arr);

                if(!in_array($businessid,$bus_arr))
                    return  $this->json(1001);

                $stoBusData = Model::ins('StoBusiness')->getRow(['id'=>$businessid],'id,ischeck');
            }

            if(!empty($stoBusData)){

                if(empty($this->params['mtoken']))
                       return $this->json('404');

                $this->params['businessid'] = $stoBusData['id'];
                
                $data = PhysicalShopModel::stoBusinessLogo($this->params);

                if($data){
                    return  $this->json('200',$data);
                }else{
                    return $this->json('1001');
                }
            }else{
                return $this->json('60008');
            }
        }

        /**
         * [alterStoBusinessLogoAction 修改店铺主图]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-24T16:33:38+0800
         * @return   [type]                   [description]
         */
        public function  alterStoBusinessLogoAction(){

            $businessid = $this->params['businessid'];
            if(empty($businessid)){
                $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid],'id,ischeck');
            }else{

                $cusData = Model::ins('CusRelation')->getList(['role'=>5,'parentid'=>$this->userid,'parentrole'=>$this->params['roleid']],'customerid,role as recoRoleType,parentrole as selfRoleType');

                if(!empty($cusData)){
                    $customerids = '';
                    foreach ($cusData as $key => $value) {
                       $customerids.= $value['customerid'] .',';
                    }
                    //var_dump($cusData);
                    $busList = Model::ins('StoBusiness')->getList(['customerid'=>['in',rtrim($customerids,',')]],'id');
                }

                //var_dump($busList);
                $bus_arr = []; 
                foreach ($busList as $key => $value) {
                     $bus_arr[] = $value['id'];
                }
                //var_dump($bus_arr);

                if(!in_array($businessid,$bus_arr))
                    return  $this->json(1001);

                $stoBusData = Model::ins('StoBusiness')->getRow(['id'=>$businessid],'id,ischeck');

            }
            if(!empty($stoBusData)){

                if(empty($this->params['mtoken']) || empty($this->params['thumb']))
                       return $this->json('404');

                $this->params['businessid'] = $stoBusData['id'];
                
                $data = PhysicalShopModel::alterStoBusinessLogo($this->params);

                if($data){
                    return  $this->json('200',$data);
                }else{
                    return $this->json('400');
                }
            }else{
                return $this->json('60008');
            }
        }




        /**
         * [stoProductListAction 实体店商品详情]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T10:27:10+0800
         * @return   [type]                   [description]
         */
        public function stoProductListAction(){
           

            if(!empty($this->params['businessid'])){
                
                if($this->Version("1.0.4")){
                    $data = PhysicalShopModel::stobusinessAndproductlist($this->params);
                }else{
                    $data = PhysicalShopModel::formartStoProductList($this->params);
                }
                if(empty($data['goodlist'])){
                    $data['business']['hasgoods'] = 0;
                    $data['business']['info'] = '店家正在装修，敬请期待！';
                }else{
                    $data['business']['hasgoods'] = 1;
                    $data['business']['info'] = '';
                }
                return  $this->json('200',$data);
                
            }else{
                return $this->json('404');
            }
        } 

        /**
         * [ownStoProductListAction 自己查看店铺列表]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-10T11:28:38+0800
         * @return   [type]                   [description]
         */
        public function ownStoProductListAction(){
           
            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1
           
            if(!empty($stoBusData)){
                //$this->params['businessid'] = $stoBusData['id'];
                $data = PhysicalShopModel::StoProductList([
                        "businessid"=>$stoBusData['id'],
                    ]);

                return  $this->json('200',$data);
            }else{
                return $this->json('60008');
            }
        }
        

        /**
         * [addStoProductAction 添加商品]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T15:13:51+0800
         */
        public  function addStoProductAction(){

            if(empty($this->params['categoryid']) || empty($this->params['thumb']))
                return $this->json("404");

            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1

            if(!empty($stoBusData)){

                $this->params['businessid'] = $stoBusData['id'];
                $result  = PhysicalShopModel::addStoProduct($this->params);

                return $this->json($result);
                /*if($data == 200){
                    return $this->json('200');
                }else{
                    return $this->json($result);
                }*/

            }else{
                return $this->json('60008');
            }
        }

        /**
         * [editStoProductAction 修改商品]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T16:07:24+0800
         * @return   [type]                   [description]
         */
        public  function editStoProductAction(){
            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1

            if(!empty($stoBusData)){

                $this->params['businessid'] = $stoBusData['id'];
                $data  = PhysicalShopModel::editStoProduct($this->params);
                if($data['code'] == 200){
                    return $this->json('200',$data['id']);
                }else{
                    return $this->json($data['code']);
                }

            }else{
                return $this->json('60008');
            }
        }

        /**
         * [delStoProductAction 删除实体店商品]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T16:17:26+0800
         * @return   [type]                   [description]
         */
        public  function delStoProductAction(){
          
            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1
           
            if(!empty($stoBusData)){

                $this->params['businessid'] = $stoBusData['id'];
                $data  = PhysicalShopModel::delStoProduct($this->params);

                if($data == 200){
                    return $this->json('200');
                }else{
                    return $this->json($data);
                }

            }else{
                return $this->json('60008');
            }
        }

        /**
         * [setRecommendAction 取消推荐]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T18:41:35+0800
         */
        public function setRecommendAction(){

            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1
          
            if(!empty($stoBusData)){

                $this->params['businessid'] = $stoBusData['id'];
                $data  = PhysicalShopModel::setRecommendStoProduct($this->params);
              
                if($data == 200){
                    return $this->json('200');
                }else{
                    return $this->json($data);
                }

            }else{
                return $this->json('60008');
            }
        }


        /**
         * [sotCodeUrlAction 返回实体店二维码]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T11:22:07+0800
         * @return   [type]                   [description]
         */
        public function sotCodeUrlAction(){

            if(empty($this->params['businessid']))
                    return  $this->json('404');
            //$shopDetails = PhysicalShopModel::getShopDetails($this->params['businessid']);
            $info = Model::ins('StoBusinessBaseinfo')->getRow(["id"=>$this->params['businessid']],"business_code,businessname");
            $img = Model::ins('StoBusinessImage')->getRow(['business_id'=>$this->params['businessid']],'thumb','id desc');
            $share = Model::ins('StoBusinessInfo')->getRow(["id"=>$this->params['businessid']],'scores,area');
          
            $sharecontent = PhysicalShopModel::sharecontent([
                    "shop_id"=>$this->params['businessid'],
                    "businessname"=>$info['businessname'],
                    "scores"=>$share['scores'],
                    "address"=>$share['area'],
                    "image"=>Img::url($img['thumb'],'300','300'),
                ]);
         
            $domain = Config::get("stobusiness.domain");
            $url = $domain."/?_apiname=Stobusiness.PhysicalShop.getsotCodeUrl&businessid=".$this->params['businessid'];

            return $this->json('200',[
                    'business_code'=>$info['business_code'],
                    'businessname'=>$info['businessname'],
                    'url'=>$url,
                    'sharecontent'=>$sharecontent
                ]);
        }


        /**
         * [stoCategory 获取商家分类]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T12:00:31+0800
         * @return   [type]                   [description]
         */
        public function stoProductCategoryListAction(){

            if(empty($this->params['businessid']))
                    return  $this->json('404');
            
            $data = PhysicalShopModel::StoProductCategoryList($this->params);
          
            if(empty($data['data']))
                 $data['data']['data'] = [];
            return  $this->json($data['code'],$data['data']);
           
        }

        /**
         * [ownStoProductListAction 查看自己的商品列表]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-10T11:37:17+0800
         * @return   [type]                   [description]
         */
        public  function ownStoProductCategoryListAction(){

            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1

            if(!empty($stoBusData)){
                $this->params['businessid'] = $stoBusData['id'];
                $data = PhysicalShopModel::StoProductCategoryList($this->params);

                if($data){
                    if(empty($data['data']))
                         $data['data'] = [];
                    return  $this->json('200',$data['data']);
                }else{
                    return $this->json('400');
                }
            }else{
                return $this->json('60008');
            }
        }

        /**
         * [addStoProductCategoryAction 添加实体店商品分类]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T14:28:20+0800
         */
        public function addStoProductCategoryAction(){
            
            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1

            if(!empty($stoBusData)){
                $this->params['businessid'] = $stoBusData['id'];
                $data  = PhysicalShopModel::addStoProductCategory($this->params);

                return $this->json($data["code"],$data['data']);
                /*if($data){
                    return $this->json('200',$data);
                }else{
                    return $this->json('400');
                }*/
            }else{
                return $this->json('60008');
            }
        }

        /**
         * [editStoProductCategoryAction 编辑分类信息]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T14:54:48+0800
         * @return   [type]                   [description]
         */
        public  function editStoProductCategoryAction(){

            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1

            if(!empty($stoBusData)){
                $this->params['businessid'] = $stoBusData['id'];
                $data  = PhysicalShopModel::editStoProductCategory($this->params);
                if($data){
                    return $this->json('200',$data);
                }else{
                    return $this->json('400');
                }
            }else{
                return $this->json('60008');
            }
        } 



        /**
         * [delStoProductCategoryAction 删除分类]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T14:41:41+0800
         * @return   [type]                   [description]
         */
        public function delStoProductCategoryAction(){
            
            $stoBusData = Model::ins('StoBusiness')->getRow(['customerid'=>$this->userid,'enable'=>1],'id,ischeck');//,'ischeck'=>1
            if(!empty($stoBusData)){
                $this->params['businessid'] = $stoBusData['id'];
                $data  = PhysicalShopModel::delStoProductCategory($this->params);
                if($data == 200){
                    return $this->json('200');
                }else{
                    return $this->json($data);
                }
            }else{
                return $this->json('60008');
            }
        }

        /**
         * [getsotCodeUrlAction 返回实体店二维码]
         * @Author   ISir<673638498@qq.com>
         * @DateTime 2017-05-09T11:23:01+0800
         * @return   [type]                   [description]
         */
        public function getsotCodeUrlAction(){

            PhysicalShopModel::getsotCodeUrl($this->params['businessid']);
        }


    
        /**
         *同时检查订单、商家、买家是否合法
         *@Author:xurui
         *@Param: array : orderno string business_id int  frommemberid int
         *@Return boolean
         */
        public static function  checkOrder ($params) {
            
            if (!empty($params)) {
                $params['status'] = 1;//订单已完成
                $sub = substr($params['pay_code'], 0,6);
                if($sub == 'NNHOTO'){
                    $oto['orderno'] = $params['pay_code'];
                    $oto['customerid'] = $params['customerid'];
                    $oto['businessid'] = $params['businessid'];
                    $check_res = Model::ins('StoOrder')->getRow($oto,'id');
                }else{
                    $orderOBJ = new StoPayFlowModel();
                    $check_res = $orderOBJ->getRow($params,'id');
                }
                if (!empty($check_res['id'])) return true;
                else return false;
            }
            return false;
        }

        /**
        * @user 店铺列表
        * @param 
        * @author jeeluo
        * @date 2017年6月28日下午8:30:51
        */
        public function storeListAction() {
            
            if(empty($this->params['selfRoleType'])) {
                return $this->json(404);
            }
            
            if($this->params['selfRoleType'] != 5) {
                return $this->json(1001);
            }
            $where = array("parentid" => $this->userid);
            if(!empty($this->params['storename'])) {
                $where['businessid'] = ["in", "select id from sto_business where businessname like '%".$this->params['storename']."%'"];
            }
            
            $result = PhysicalShopModel::storeList($where);
            
            return $this->json(200, $result['data']);
        }
        
        /**
        * @user 添加门店
        * @param 
        * @author jeeluo
        * @date 2017年6月29日下午7:33:17
        */
        public function addStoreAction() {
            if(empty($this->params['selfRoleType']) || empty($this->params['mobile']) || empty($this->params['valicode'])) {
                return $this->json(404);
            }
            if($this->params['selfRoleType'] != 5) {
                return $this->json(1001);
            }

            if(phone_filter($this->params['mobile'])) {
                return $this->json(20006);
            }
            
            // 校验该手机号码是否已经有实体店
            $isSto = CommonModel::getMobileExistsSto(array("mobile"=>$this->params['mobile'])); // false 为有 true为无
            if($isSto) {
                return $this->json(20301);
            }
            
            $phone['valicode'] = strtoupper($this->params['valicode']);
            $phone['mobile'] = $this->params['mobile'];
            $cus = new CusCustomerModel();
            $validaStatus = $cus->compare($phone, self::sendType);
            if(!$validaStatus) {
                return $this->json(20005);
            }
            $this->params['customerid'] = $this->userid;
            //
            $result = PhysicalShopModel::addStore($this->params);
            
            return $this->json($result['code'], $result['data']);
        }
        
        /**
        * @user 子店信息
        * @param 
        * @author jeeluo
        * @date 2017年6月30日下午4:16:33
        */
        public function storeInfoAction() {
            if(empty($this->params['id'])) {
                return $this->json(404);
            }
            // 查询用户选择的id是否有访问权限
            $storeInfo = Model::ins("StoStore")->getRow(["id"=>$this->params['id']],"customerid,parentid");
            if(empty($storeInfo)) {
                return $this->json(1000);
            }
            
            if($storeInfo['parentid'] != $this->userid) {
                return $this->json(1001);
            }
            
            $data = Model::new("User.Amount")->getUserAmount(array("customerid"=>$storeInfo['customerid']));
            
            $stoFlow = Model::new("User.UserAmount")->getStoFlowAmount(array("customerid"=>$storeInfo['customerid']));
            $data['stoFlow'] = !empty($stoFlow) ? DePrice($stoFlow) : '0.00';
            $stoShare = Model::new("User.UserAmount")->getStoShareAmount(array("customerid"=>$storeInfo['customerid'],"role"=>5));
            $data['stoFlowShare'] = !empty($stoShare) ? DePrice($stoShare) : '0.00';
            $stoFlowCom = Model::new("User.UserAmount")->getStoFlowComAmount(array("customerid"=>$storeInfo['customerid']));
            $data['stoFlowCom'] = !empty($stoFlowCom) ? DePrice($stoFlowCom) : '0.00';

            // 查询门店用户数量
            $data['stoUserCount'] = Model::new("User.UserAmount")->stoFansUserCount(array("userid"=>$storeInfo['customerid']));
            // 店铺用户id值
            $data['stoUserId'] = $storeInfo['customerid'];
            
            return $this->json(200, $data);
        }
    }