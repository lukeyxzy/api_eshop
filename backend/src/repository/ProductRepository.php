<?php

namespace App\repository;

use App\model\Product;
use App\model\ErrorLog;
use App\exception\exception;
use PDO;

class ProductRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }


    function addProduct(Product $product) :Product {
        

        $stmt = $this->pdo->prepare("INSERT INTO product(name, description, price, image_name, user_id, category_id) VALUES (:name, :description, :price, :image_name, :user_id, :category_id)");

        $name = $product->getName();
        $description = $product->getDescription();
        $price = $product->getPrice();
        $image_name = $product->getImageFileName();
        $userId = $product->getUserId();
        $categoryId = $product->getCategoryId();

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":image_name", $image_name);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":category_id", $categoryId);


        $stmt->execute();
        $productId = $this->pdo->lastInsertId();
        $product->setId($productId); 
        return $product;
        }


    

        function getAllProducts() :array {
                   
        $stmt = $this->pdo->prepare("SELECT * FROM product");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
        }


        function getProductById($id) :array {
        $stmt = $this->pdo->prepare("SELECT * FROM product WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $productData;
        }


 

    public function getProductsByCategoryId($category_id): array {

        
        $stmt = $this->pdo->prepare("SELECT * FROM product WHERE category_id=:category_id");
        $stmt->bindParam(":category_id", $category_id);

        $stmt->execute();
        $productData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $productData;

    }



 



    }











?>