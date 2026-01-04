<?php

class OrderLineRepository {

    public string $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function getOrderLines(): array {
        $lines = file($this->filePath);

        $orderLines = [];
        foreach ($lines as $line) {

            [$name, $price, $inStock] = explode(';', trim($line));

            $orderLines[] = new OrderLine(
                $name, floatval($price), boolval($inStock));
        }
        return $orderLines;
    }
}
