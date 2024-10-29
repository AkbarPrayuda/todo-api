<?php 

class Response {

    public static function success(string $message, $data = [], $status = 200)
    {
        header("Content-type: Application/json");

        $response = array("message" => $message, "data" => $data, "status" =>  $status);

        return json_encode($response);
    }

    public static function error(string $message, $status = 404) {
        header("Content-type: Application/json");

        $response = array("message" => $message, "status" =>  $status);

        return json_encode($response);
    }

}

?>