<?php

namespace Application\Commands;

use Application\Services\AuthenticationService;

class SignOutCommand
{
    public function __construct(
        private AuthenticationService $authenticationService
    ) {
    }

    public function execute(): void
    {
        $this->authenticationService->signOut();
    }
}
