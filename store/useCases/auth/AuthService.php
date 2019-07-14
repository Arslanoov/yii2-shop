<?php

namespace store\useCases\auth;

use store\repositories\UserRepository;
use store\forms\auth\LoginForm;
use store\entities\User\User;
use DomainException;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function auth(LoginForm $form): User
    {
        $user = $this->users->findByUsernameOrEmail($form->username);

        if (!$user || !$user->validatePassword($form->password)) {
            throw new DomainException('Неверное имя пользователя или пароль.');
        }

        if (!$user->isActive()) {
            throw new DomainException('Пользователь не активирован');
        }

        return $user;
    }
}