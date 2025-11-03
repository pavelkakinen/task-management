<?php

class OrderLineRepository {

    public string $filePath;

    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    public function getOrderLines(): array {
        return [];
    }
}
