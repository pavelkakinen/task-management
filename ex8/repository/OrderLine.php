<?php

class OrderLine {

    public string $productName;
    public float $price;
    public bool $inStock;

    public function __construct(string $productName, float $price, bool $inStock) {
        $this->productName = $productName;
        $this->price = $price;
        $this->inStock = $inStock;
    }


}
