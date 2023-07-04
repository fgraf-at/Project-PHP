<?php

namespace Application\Interfaces;

use Application\Entities\Product;

interface ProductRepository
{
    public function addProductToDatabase($manufacturer, $userId, $name, $price): ?int;
    public function canEdit(int $id, int $userId): bool;
    public function edit(int $id, string $producer, string $name);
    public function getRatingsFromProduct(int $productId): array;
    public function getProduct(int $id): ?Product;
    public function getProductForFilter(string $filter):array;
    public function getAverageRatingForProduct(int $productId): float;
}