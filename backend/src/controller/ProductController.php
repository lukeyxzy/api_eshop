<?php
namespace App\controller;

use App\service\ProductService;
use App\model\ErrorLog;
use Exception;


class ProductController {
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
        echo json_encode(["success" => true]);
    }



    public function getAllProducts() {

        try {
            $productsList = $this->productService->getAllProducts();
            echo json_encode($productsList);
        }
        catch(Exception $e) {
            ErrorLog::logError($e);
            http_response_code(500);
            echo json_encode(["error" => "Chyba na straně serveru"]);
        }

        }
        

    public function getProductById($id) {     
        if(empty($id) || $id < 0) {
        throw new ValidationException("Neplatné id produktu");
        }
            $product = $this->productService->getProductById($id);
            echo json_encode($product);


    }




    public function getProductsByCategoryId($id) {

            if(empty($id) || $id < 0) {
            throw new ValidationException("Přišlo špatné id kategorie");
            }

            $products = $this->productService->getProductsByCategoryId($id);

            echo json_encode($products);



    }
}

?>