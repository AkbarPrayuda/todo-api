<?php 

class Response {


    public static function  success($message, $data)
    {
        header("Content-type: Application/json");

        $response = array("message" => $message, "data" => $data);

        return json_encode($response);
    }

}

?>