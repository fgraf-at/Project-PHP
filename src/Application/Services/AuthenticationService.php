<?php

namespace Application\Services;

class AuthenticationService
{
    const SESSION_USER_ID = "userId";

    public function __construct(private \Application\Interfaces\Session $session)
    {
    }

    public function getUserId() : ?string
    {
        return $this->session->get(self::SESSION_USER_ID, null);
    }

    public function signIn(string $userId): void
    {
        $this->session->put(self::SESSION_USER_ID, $userId);
    }

    public function signOut(): void
    {
        $this->session->delete(self::SESSION_USER_ID);
    }
}
