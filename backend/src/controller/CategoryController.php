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

       try {
        $catName = json_decode(file_get_contents("php://input"));

        if (empty($catName)) {
            throw new ValidationException("Nepřišla data pro vytvoření nové kategorie.");
        }
        
        $result = $this->categoryService->addCategory($catName, $token);

        echo json_encode(["success" => true]);
        }
        catch(UnauthorizedException | ValidationException $e) {
        http_response_code(400);
        echo json_encode(["error" => $e->getMessage()]);
       }
       catch(Exception $e) {
        ErrorLog::logError($e);
        http_response_code(500);
        echo json_encode(["error" => "Nastala chyba na serveru, opakujte akci později."]);
       }

    }


    public function getCategoryById($id) {
        try {

        if(empty($id) || $id < 0) {
           throw new ValidationException("Chybí parametr Id nebo je ve špatném formátu.");
        }

        $category = $this->categoryService->getCategoryById($id);
        echo json_encode($category);

        }
        catch(ValidationException $e) {
            echo json_encode(["error" => $e]);
        }
        catch(Exception $e) {
            ErrorLog::logError($e);
        }


    }

    public function getAllCategories() {

        try {
            $categories =  $this->categoryService->getAllCategories();
            echo json_encode($categories);
        }
        
        catch(Exception $e) {
            http_response_code(500);
            ErrorLog::logError($e);
            echo json_encode(["error" => "Nastala chyba na serveru, opakujte akci později."]);
        }

        
    }
}



?>
