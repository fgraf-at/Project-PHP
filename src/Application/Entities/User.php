<?php

namespace Application\Entities;

class User
{
    public function __construct(
        private int $id,
        private string $userName
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
}
