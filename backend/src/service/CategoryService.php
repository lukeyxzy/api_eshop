<?php

namespace App\service;


use App\model\Category;
use App\repository\CategoryRepository;
use App\service\UserService;
use App\utils\JwtHelper;

class CategoryService {
    private CategoryRepository $categoryRepository;
    private JwtHelper $jwtHelper;

    public function __construct(CategoryRepository $categoryRepository, JwtHelper $jwtHelper ) {
        $this->categoryRepository = $categoryRepository;
        $this->jwtHelper = $jwtHelper;
    }


    public function addCategory($catData, $token) {

     $tokenResult = $this->jwtHelper->validateToken($token);

    if(!$tokenResult || $tokenResult->role !== 1) {
     throw new UnauthorizedException("Nemáte oprávnění na přidávání kategorií");
     }

     $category = new Category($catData["categoryName"], $tokenResult->userId);

     if (!$category) {
      throw new Exception("Nepovedlo se vytvorit objekt category pomoci tridy Category");
     }

     $resultCategory = $this->categoryRepository->addCategory($category);

      if (!$resultCategory) {
      throw new Exception("Spatny result z categoryRepository->addCategory");
      }
    
     return true;
        
    }



    public function getCategoryById($id) :Category {

        $categoryData = $this->categoryRepository->getCategoryById($id);

        if(!$categoryData) {
        throw new ValidationException("Kategorie dle Id nenalezena.");
        }

        $category = new Category($categoryData["name"], $categoryData["user_id"],$categoryData["id"]);

        return $category;

    }


    public function getAllCategories(): array {
        $categoryList = [];
       $categories =  $this->categoryRepository->getAllCategories();
       
       foreach($categories as $category) {
        $categoryList[] = new Category($category["name"], $category["user_id"], $category["id"]);
       }

       return $categoryList;

    }

}