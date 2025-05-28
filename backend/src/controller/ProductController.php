<?php
namespace App\controller;

use App\service\ProductService;
use App\model\ErrorLog;
use Exception;
use App\exception\ValidationException;
use App\exception\AuthenticationException;
use Firebase\JWT\ExpiredException;
use App\controller\BaseController;

class ProductController extends BaseController {
    private ProductService $productService;

    public function __construct(ProductService $productService) {
        $this->productService = $productService;
    }

    public function addProduct($token) {
        if(!$token) {
            throw new AuthenticationException("Nejste příhlášení.");
        }  

        if(empty($_POST["categoryId"]) || empty($_POST["name"]) || empty($_POST["description"]) || empty($_POST["price"]) || empty($_FILES["image"])) {
            throw new ValidatationException("Nepřišli všechny hodnoty pro vytvoření produktu.");
        }

            $categoryId = $_POST["categoryId"];
            $name = $_POST["name"];
            $description = $_POST["description"];
            $price = $_POST["price"];
            $image = $_FILES["image"];
        
        
        $this->productService->addProduct($token, $categoryId, $name, $description, $price, $image);
               $this->jsonResponse(["success" => true]);
    }



    public function getAllProducts() {
            $productsList = $this->productService->getAllProducts();
             $this->jsonResponse($productsList);
        }
        

    public function getProductById($id) {     
        if(empty($id) || $id < 0) {
        throw new ValidationException("Neplatné id produktu");
        }
            $product = $this->productService->getProductById($id);
          $this->jsonResponse($product);
    }




    public function getProductsByCategoryId($id) {
            if(empty($id) || $id < 0) {
            throw new ValidationException("Přišlo špatné id kategorie");
            }
            $products = $this->productService->getProductsByCategoryId($id);
            $this->jsonResponse($products);
    }
}

?>