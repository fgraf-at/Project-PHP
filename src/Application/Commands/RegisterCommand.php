<?php

namespace Application\Commands;

use Application\Interfaces\UserRepository;

class RegisterCommand
{

    public function __construct(
        private UserRepository $userRepository
    ) { }

    public function execute(string $username, string $password): ?String
    {
        $username = trim($username);
        if($this->userRepository->getUserForUsername($username) !== null) {
            return "Username already exists";
        }
        else if(!(strlen($username) == 0 || strlen($password) <= 0)) {
            if($this->userRepository->createUser($username, $password) === null) {
               return "User could not be created";
            }
         }else{
            return "Invalid username or password provided!";
        }
        return null;
    }
}
