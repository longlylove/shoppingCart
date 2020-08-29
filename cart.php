<?php
class cart {
    private $products = [
        [ "name" => "Sledgehammer", "price" => 125.75 ],
        [ "name" => "Axe", "price" => 190.50 ],
        [ "name" => "Bandsaw", "price" => 562.13 ],
        [ "name" => "Chisel", "price" => 12.90 ],
        [ "name" => "Hacksaw", "price" => 18.45 ]
    ];

    function GetProducts(){
        return $this -> products;
    }

    function GetProductByName($productName){
        $product = array_search($productName,array_column($this->products, "name"));
        //echo $this -> products[$product]['name']." ".$this -> products[$product]['price'];
        return $this -> products[$product];
    }

    function convertToObject($array) {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this -> convertToObject($value);
            }
            $object->$key = $value;
        }
        return $object;
    }
}
?>