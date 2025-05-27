<?php


namespace App\controller;

use App\service\CategoryService;
use App\model\ErrorLog;
use App\exception\UnauthorizedException;
use App\exception\ValidationException;
use Exception;

class CategoryController{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService) {
        $this->categoryService = $categoryService;
    }


    public function addCategory($token) {
        $catName = json_decode(file_get_contents("php://input"));
        if (empty($catName)) {
            throw new ValidationException("Nepřišla data pro vytvoření nové kategorie.");
        }
        $result = $this->categoryService->addCategory($catName, $token);
        echo json_encode(["success" => true]);

    }


    public function getCategoryById($id) {

        if(empty($id) || $id < 0) {
           throw new ValidationException("Chybí parametr Id nebo je ve špatném formátu.");
        }

        $category = $this->categoryService->getCategoryById($id);
        echo json_encode($category);


    }

    public function getAllCategories() {

            $categories =  $this->categoryService->getAllCategories();
            echo json_encode($categories);

    }
}



?>
