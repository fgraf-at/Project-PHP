<?php

namespace Application\Commands;


use Application\Services\AuthenticationService;
use Application\Interfaces\UserRepository;

class SignInCommand
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private UserRepository $userRepository
    ) {
    }

    public function execute(string $username, string $password): bool
    {
        $this->authenticationService->signOut();
        $user = $this->userRepository->getUserForUsernameAndPassword($username, $password);
        if ($user != null) {
            $this->authenticationService->signIn($user->getId());
            return true;
        }
        return false;
    }
}
