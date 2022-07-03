<?php

namespace MVC\Core\Models\Auth;

use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use MVC\Core\Models\Abstract\DbModel;

class User extends DbModel
{
    public string $firstname = '';
    public string $lastname = '';
    public string $email = '';
    public string $password = '';
    public string $passwordConfirm = '';
    public bool $terms = false;

    public function __construct()
    {
        parent::__construct();
    }

    public function table(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return [
            'firstname',
            'lastname',
            'email',
            'password',
        ];
    }

    /* @throws InvalidArgumentException */
    public function save(): bool
    {
        if (!$this->isValid()) {
            throw new InvalidArgumentException('Register Model is not complying with the rules being set');
        }
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        return parent::save();
    }

    public function rules(): array
    {
        return [
            'password' => [self::RULE_REQUIRED => true, self::RULE_MIN => 6, self::RULE_MAX => 255],
            'passwordConfirm' => [self::RULE_MATCH => 'password'],
            'email' => [self::RULE_REQUIRED => true, self::RULE_EMAIL => true, self::RULE_MAX => 255, self::RULE_UNIQUE => true],
        ];
    }

    public function login(): bool
    {
        $user = $this->load('email', $this->email);;
        return $user && password_verify($this->password, $user->password);
    }

}