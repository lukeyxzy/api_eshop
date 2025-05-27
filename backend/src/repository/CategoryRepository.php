<?php


namespace App\repository;

use App\model\Category;
use App\model\ErrorLog;
use PDO;

class CategoryRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }



    public function addCategory(Category $category): Category {

        $stmt = $this->pdo->prepare("INSERT INTO category(name, user_id) VALUES (:name, :user_id)");
        
        if(!$stmt) {
            throw new PDOException("Chyba při přípravě dotazu.");
        }

        $name = $category->getName();
        $userId = $category->getUserId();

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":user_id", $userId);
        

        if(!$stmt->execute()) {
            $error = $stmt->errorInfo();
            throw new PDOException("Chyba při provedení dotazu". $error[2]);
        }
        

        $categoryId = $this->pdo->lastInsertId();
        $category->setId($categoryId); 
        return $category;
    
    }
 

    public function getCategoryById($id): array {
                
        $stmt = $this->pdo->prepare("SELECT * FROM category WHERE id=:id");
        $stmt->bindParam(":id", $id);

        $stmt->execute();

        $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $categoryData;
    }

public function getAllCategories():array {

        $stmt = $this->pdo->prepare("SELECT id, name, user_id  FROM category");
        if(!$stmt->execute()) { 
            throw new Exception("Nepodarilo se vybrat kategorie z databze");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




}











?>