<?php

namespace Anso\Lib\Auth;

use Anso\Lib\Contract\Authenticatable;
use Anso\Lib\Exception\AuthorizationException;
use Anso\Lib\Contract\EntityManager;

class Authenticator
{
    private string $subject;
    private Authenticatable $user;

    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param string $login
     * @param string $password
     * @return Authenticatable
     * @throws AuthorizationException
     */
    public function attempt(string $login, string $password): Authenticatable
    {
        $this->user = $this->em->findOneBy($this->subject, [
            'login' => $login
        ]);

        if (!$this->user) {
            throw new AuthorizationException("User with $login login was not found");
        }

        if (!password_verify($password, $this->user->getPassword())) {
            throw new AuthorizationException("Invalid credentials");
        }

        $_SESSION['user'] = serialize($this->user);

        return $this->user;
    }

    /**
     * @throws AuthorizationException
     */
    public function user()
    {
        if (!isset($_SESSION['user'])) {
            throw new AuthorizationException('Unauthorized');
        }

        return unserialize($_SESSION['user']);
    }

    public function authorized(): bool
    {
        return isset($_SESSION['user']);
    }
}