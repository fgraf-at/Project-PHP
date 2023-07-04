<?php

namespace Application\Interfaces;

interface UserRepository
{
    public function getUserForUsernameAndPassword(string $username, string $password): ?\Application\Entities\User;
    public function getUser(int $id): ?\Application\Entities\User;
    public function createUser(string $userName, string $password) : ?int;

    public function getUserForUsername(string $username): ?\Application\Entities\User;
}
