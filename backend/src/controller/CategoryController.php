<?php


namespace App\controller;

use App\controller\BaseController;
use App\service\CategoryService;
use App\model\ErrorLog;
use App\exception\ValidationException;
use Exception;

class CategoryController extends BaseController {
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }


    public function addCategory($token) {
        $data = $this->getJsonInput();
        $result = $this->categoryService->addCategory($data, $token);
        $this->jsonResponse(["success"=> true]);
    }


    public function getCategoryById($id) {
        if(empty($id) || $id < 0) {
           throw new ValidationException("Chybí parametr Id nebo je ve špatném formátu.");
        }
        $category = $this->categoryService->getCategoryById($id);
        $this->jsonResponse($category);
    }

    public function getAllCategories() {
            $categories =  $this->categoryService->getAllCategories();
            $this->jsonResponse($categories);
    }
}



?>
