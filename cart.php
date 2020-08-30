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
        return $this -> products[$product];
    }
}
?>