<?php

namespace App\repository;

use App\model\ErrorLog;
use App\model\Order;
use App\model\DeliveryAddress;
use PDO;


class OrderRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }


public function addOrder(Order $order) : Order {

    $stmt = $this->pdo->prepare("INSERT INTO order_table(date, price, payment_method, user_id, guest_name, guest_surname, guest_email) VALUES 
    (:date, :price, :payment_method, :user_id, :guest_name, :guest_surname,:guest_email)");


    $stmt->execute([
            'date' => $order->getDate(),
            'price' =>$order->getPrice(),
            'payment_method' => $order->getPaymentMethod(),
            'user_id' => $order->getUserId(),
            'guest_name' => $order->getGuestName(),
            'guest_surname' => $order->getGuestSurname(),
            'guest_email' => $order->getGuestEmail()
    ]);
    $orderId = $this->pdo->lastInsertId();
    $order->setId($orderId); 
    return $order;
}


public function addOrderItems(Order $order) :bool {
    $order_id = $order->getId();
    $orderItems =  $order->getOrderItems();
    $errors = [];

    foreach($orderItems as $orderItem) {
        
    $stmt = $this->pdo->prepare("INSERT INTO order_item(price, order_table_id, product_id) VALUES 
    (:price, :order_table_id, :product_id)");

   if(!$stmt->execute([
            'price' => $orderItem->getItemPrice(),
            'order_table_id' => $order_id,
            'product_id' => $orderItem->getProductId()
        ])) {
            $errors[] = true;

        }

    }

    if($errors) {
        return false;
    }
    return true;
} 


public function getAllOrders() :array {
        
    $stmt = $this->pdo->prepare("SELECT ot.id, ot.date, ot.price, u.email, guest_email FROM order_table ot LEFT JOIN user u ON ot.user_id = u.id ORDER BY ot.date DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}


public function addDeliveryAddress(DeliveryAddress $deliveryAddress) :DeliveryAddress {


    $stmt = $this->pdo->prepare("INSERT INTO delivery_address(street, street_number, city, zip_code, order_table_id) VALUES 
    (:street, :street_number, :city, :zip_code, :order_table_id)");


    $stmt->execute([
            'street' => $deliveryAddress->getStreet(),
            'street_number' => $deliveryAddress->getStreetNumber(),
            'city' => $deliveryAddress->getCity(),
            'zip_code' => $deliveryAddress->getZipCode(),
            'order_table_id' => $deliveryAddress->getOrderId()
    ]);
            $deliveryAddressId = $this->pdo->lastInsertId();
            $deliveryAddress->setId($deliveryAddressId); 
            return $deliveryAddress;
}



public function getOrdersByUserId($userId) :array {
    
    $stmt = $this->pdo->prepare("SELECT ot.id, ot.date, ot.price, u.email, guest_email FROM order_table ot LEFT JOIN user u ON ot.user_id = u.id 
    WHERE ot.user_id = :user_id 
    ORDER BY ot.date DESC");

    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

public function checkPermissionsToViewOrderDetails($userId, $orderId) :bool {
    $stmt = $this->pdo->prepare("SELECT ot.id FROM order_table ot JOIN user u ON ot.user_id = u.id 
    WHERE ot.user_id = :user_id AND ot.id = :order_id");
    $stmt->execute([':user_id' => $userId,':order_id' => $orderId]);
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}


public function getOrderDetails($orderId) :array {
    $stmt = $this->pdo->prepare("SELECT da.street, da.street_number, da.city, da.zip_code, ot.date, ot.price, ot.payment_method, ot.guest_name, ot.guest_surname, ot.guest_email ,u.name, u.surname, u.email FROM delivery_address da JOIN order_table ot ON da.order_table_id = ot.id LEFT JOIN user u ON ot.user_id = u.id WHERE ot.id = :order_id");
    $stmt->execute([':order_id' => $orderId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);

}

public function getOrderItemsByOrderId($orderId) :array {
    $stmt = $this->pdo->prepare("SELECT oi.price AS order_item_price, p.name, p.description, p.price, p.image_name FROM order_item oi JOIN product p ON oi.product_id = p.id WHERE oi.order_table_id = :order_id");
     $stmt->execute([':order_id' => $orderId]);
     return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}