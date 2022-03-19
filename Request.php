<?php
class Request{

    public $url;
    public $query;

    function __construct()
    {
        $this->url = null;
        $this->query = null;
    }

    public static function search($url,$query){
        $target = $url . str_replace(" ","%20",$query);
        try{
            $data = [
                'collection' => 'drinks'
            ];
        
            $curl = curl_init($target);
        
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_POST, true);
            curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($curl,CURLOPT_HTTPHEADER,[
                'X-RapidAPI-Host: the-cocktail-db.p.rapidapi.com',
                'X-RapidAPI-Key: 1',
                'Content-Type: application/json'
            ]);
        
            $response = curl_exec($curl);
            $decode = json_decode($response);
            if(is_object($decode)){
                $drinks = $decode->drinks;
            }            
            curl_close($curl);
            if(isset($drinks)) return $drinks;            
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }
}
?>