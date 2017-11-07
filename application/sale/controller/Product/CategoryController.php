<?php
namespace app\sale\controller\Product;
use app\sale\ActionController;

use app\lib\Model;
use app\model\Product\ProductModel;

class CategoryController extends ActionController{

	/**
     * 固定不变
     */
    public function __construct(){
        parent::__construct();
        
    }
    

    public function indexAction(){

        $viewData = [
            'title' => '分类列表'
        ];
        return $this->view($viewData);

    }

    /**
     * [categoryAction 获取所有分类]
     * @Author   ISir<673638498@qq.com>
     * @DateTime 2017-10-10T10:15:16+0800
     * @return   [type]                   [description]
     */
    public function getcategoryAction(){
        $category = ProductModel::formartCategory();
        return $this->json($category['code'],$category['data']);
    }

}