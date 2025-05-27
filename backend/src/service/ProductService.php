<?php

namespace App\service;

use App\model\ErrorLog;
use App\model\Product;
use App\model\Database;
use App\utils\JwtHelper;
use App\repository\productRepository;

class ProductService {
    private ProductRepository $productRepository;
    private JwtHelper $jwtHelper;

    public function __construct(ProductRepository $productRepository, JwtHelper $jwtHelper) {
        $this->productRepository = $productRepository;
        $this->jwtHelper = $jwtHelper;
    }

    public function addProduct($token, $categoryId, $name, $description, $price, $image) {    

        $userData = $this->jwtHelper->validateToken($token);

        if(!$userData) {
            throw new UnauthorizedException("Nemáte oprávnění na přidávání produktů");
        }


       $image_error = $_FILES["image"]["error"];
        $image_size = $_FILES["image"]["size"];
       $image_name = $_FILES["image"]["name"];
        $image_tmp_path = $_FILES["image"]["tmp_name"];

        if($image_error) {
        throw new ValidatationException($image_erorr);
                }
        if ($image_size > 10000000) {
            throw new ValidatationException("Obrázek je příliš velký.");
        }
        
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $allowed_img_extensions = ["jpg", "jpeg", "png", "webp"];
        
        if (!in_array($image_extension, $allowed_img_extensions)) {
        throw new ValidatationException("Obrázek nemá povolenou příponu (jpg, jpeg, png, webp)");
        }
        
        $new_image_name = uniqid("img_", true) . "." . $image_extension;
        $image_upload_path =  "../images/uploads/" . $new_image_name;

        if (!move_uploaded_file($image_tmp_path, $image_upload_path)){
            throw new UploadException("Nepodařilo se nahrát obrázek.");
        }
        
        $product = new Product($name, $description, $price, $new_image_name, $userData->userId, $categoryId);
        
        if(!$product) {
            throw new Exception("nepodarilo se vytvorit objekt Product");
        }

        $resultProduct = $this->productRepository->addProduct($product);

        if(!$resultProduct) {
         throw new Exception("nevrátil se user z productService.");
        }
    }
    
    
    public function getAllProducts() {

        return $this->productRepository->getAllProducts();


    }

    public function getProductById($id) {

    $productData = $this->productRepository->getProductById($id);

    if(!$productData) {
        throw new ValidationException("Produkt s tímto id neexistuje.");
    }
    $product = new Product($productData["name"], $productData["description"], $productData["price"], $productData["image_name"], $productData["user_id"], $productData["category_id"], $productData["id"]);
    return $product;
    }


    public function getProductsByCategoryId($category_id): array {

        
    $productsByCategoryId = $this->productRepository->getProductsByCategoryId($category_id);
    $products = [];
            
    foreach ($productsByCategoryId as $product) {
    $products[] = new Product($product["name"],$product["description"],$product["price"],$product["image_name"],$product["user_id"],$product["category_id"],$product["id"]);
    }
    
    return $products;
    }



}