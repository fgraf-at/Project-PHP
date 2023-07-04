<?php

namespace Application\Entities;

class Product
{
    public function __construct(
        private int $id,
        private string $name,
        private float $price,
        private string $producer,
        private int $userId
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
    public function getProducer(): string
    {
        return $this->producer;
    }
    public function getUserId(): int
    {
        return $this->userId;
    }
}
