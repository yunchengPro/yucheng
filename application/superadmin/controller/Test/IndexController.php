<?php
namespace app\superadmin\controller\Test;
use app\superadmin\ActionController;

use app\lib\Db;
use app\model\StoBusiness\StobusinessModel;
use app\lib\Model;

class IndexController extends ActionController{

		/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    
    /**
     * [indexAction description]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-06-14T17:20:01+0800
     * @return   [type]                   [description]
     */
    public function indexAction(){
    	exit();
    // 	$insert_arr = [
    // 			'14698787898',
				// '14687587522',
				// '14789778785',
				// '14789655878',
				// '14789868785',
				// '14789578586',
				// '14789658787',
				// '14789658758',
				// '14789658798',
				// '14789965887',
				// '14799688778',
				// '14765889687',
				// '14866653656',
				// '14869878669',
				// '14879565875',
				// '14879657569',
				// '14896988778',
				// '14869963575',
				// '14869877856',
                //  14222222222
    // 	];

    	// $insert_arr = [
    	// 	'11222226761'
    	// ];
        //$value = intval('11222222200');
        // for($i=0;$i<=95;$i++){
        //     $value = $value + $i;
            // var_dump($value);
            
       

    	// foreach ($insert_arr as $key => $value) {

    	// 	$inserV = [
    	// 		'mobile' => strval($value),
    	// 		'username' => strval($value),
    	// 		'createtime'=> date('Y-m-d H:i:s')
    	// 	];
     //        var_dump($inserV);
    	// 	$ret = Model::ins('CusCustomer')->insert($inserV);
    	// 	var_dump($ret);
    	// 	$reaData1 = [
    	// 		'customerid' => $ret,
    	// 		'role' => 1,
    	// 		'parentrole' => 1,
    	// 		'parentid' => 2107,
    	// 		'grandparole'=> 1,
    	// 		'grandpaid' => 1821,
    	// 		'addtime' => date('Y-m-d H:i:s')
    	// 	];

    	// 	$reaData2 = [
    	// 		'customerid' => $ret,
    	// 		'role' => 5,
    	// 		'parentrole' => 8,
    	// 		'parentid' => 2107,
    	// 		'grandparole'=> 8,
    	// 		'grandpaid' => 1821,
    	// 		'addtime' => date('Y-m-d H:i:s')
    	// 	];

    	// 	var_dump(Model::ins('CusRelation')->insert($reaData1));
    	// 	var_dump(Model::ins('CusRelation')->insert($reaData2));

            


    	// 	$roleData1 = [
    	// 		'customerid' => $ret,
    	// 		'role' => 1,
    	// 		'addtime' => date('Y-m-d H:i:s')
    	// 	];

    	// 	$roleData2 = [
    	// 		'customerid' => $ret,
    	// 		'role' => 8,
    	// 		'addtime' => date('Y-m-d H:i:s')
    	// 	];

    	// 	var_dump(Model::ins('CusRole')->insert($roleData1));
    	// 	var_dump(Model::ins('CusRole')->insert($roleData2));

     //        $RecoCount1 = Model::ins('CusRecoCount')->getRow(['customerid'=>$ret,'role'=>8],'id,reco_sto_count');
            
     //        if(!empty($RecoCount1)){
     //            var_dump(Model::ins('CusRecoCount')->update(['reco_sto_count'=>$RecoCount1['reco_sto_count'] + 1],['id'=>$RecoCount1['id']]));
     //        }else{
     //            $insertCountData = [
     //                'customerid' =>  $ret,
     //                'role' => 8,
     //                'parentid' => 2107,
     //                'parentrole' => 8,
     //                'reco_sto_count' => 1,

     //            ];
     //            var_dump(Model::ins('CusRecoCount')->insert($insertCountData));
     //        }

     //        $RecoCount2 = Model::ins('CusRecoCount')->getRow(['customerid'=>$ret,'role'=>1],'id,reco_sto_count');
            
     //        if(!empty($RecoCount2)){
     //            var_dump(Model::ins('CusRecoCount')->update(['reco_sto_count'=>$RecoCount2['reco_sto_count'] + 1],['id'=>$RecoCount2['id']]));
     //        }else{
     //            $insertCountData = [
     //                'customerid' =>  $ret,
     //                'role' => 1,
     //                'parentid' => 2107,
     //                'parentrole' => 1,
     //                'reco_sto_count' => 1,

     //            ];
     //            var_dump(Model::ins('CusRecoCount')->insert($insertCountData));
     //        }
            

    	//}
    	

    }
}