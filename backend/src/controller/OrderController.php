<?php
namespace App\controller;

use App\service\OrderService;
use App\model\ErrorLog;
use App\exception\ValidationException;
use App\exception\AuthenticationException;
use Exception;


class OrderController {
    private OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }


    public function placeOrder($token) {
      try {
      $data = json_decode(file_get_contents("php://input"), true);

        if(empty($data["deliveryAddress"]) || empty($data["paymentMethodValue"]) || empty($data["orderItems"])) {
            echo json_encode(["error" => "chybi vsechny hodnoty objednávky"]);
            return;
        }

        $postedOrderWithDbId = $this->orderService->createOrder($data, $token);

        $resultDeliveryAddress = $this->orderService->createDeliveryAddress($data, $postedOrderWithDbId);

        $resultOrderItems = $this->orderService->addOrderItemsToDb($postedOrderWithDbId);


        if (!$resultOrderItems || !$resultDeliveryAddress) {
          echo json_encode(["error" => "Neúspěšné přijetí objednávky"]);
          return;
        }
        
        echo json_encode($postedOrderWithDbId->getId());
        }
        catch(Exception $e) {
          ErorrLog::logError($e);
          http_response_code(500);
        }
    }




    public function getOrders($token) {
      try {
      $result = $this->orderService->getOrders($token);
  
      echo json_encode($result);
      }
      catch(Exception $e) {
        ErrorLog::logError($e);
        http_response_code(500);
      }
    }


  public function getOrderDetails($orderId, $token) {

    try {
     $output =  $orderDetails = $this->orderService->getOrderDetails($orderId, $token);     

     echo json_encode($output);
    }
    catch(ValidationException | AuthenticationException $e) {
      echo json_encode(["error" => $e->getMessage()]);
    }
    catch(Exception $e) {
           ErrorLog::logError($e);
    }
    
  } 
  
  
}

?>