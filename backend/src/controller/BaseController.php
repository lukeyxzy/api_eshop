<?php

namespace App\controller;

use App\exception\ValidationException;

abstract class BaseController {

    protected function getJsonInput(bool $outputToAssocArray = true) :array {
        $data = json_decode(file_get_contents("php://input"), $outputToAssocArray);
        if (empty($data)) {
            throw new ValidationException("Nepřišly potřebná data."); 
        };
        return $data;
}

protected function jsonResponse($data, int $status = 200) :void {
    header("Content-type: application/json");
    http_response_code($status);
    echo json_encode($data);
}


}