<?php

namespace App\Http\Forms;

use App\DTO\User;

class RegisterForm extends AbstractForm
{

    private \stdClass $user;

    public function __construct(private array $registerData)
    {
        $this->user = $this->data($this->registerData);
    }

    public function getUserDto(): User
    {
        return User::make(
          firstName: $this->user->firstName,
          lastName: $this->user->lastName,
          email: $this->user->email,
          password: password_hash($this->user->password, PASSWORD_DEFAULT)
        );
    }

    public function errors(): array
    {
        $this->validate();

        return $this->errors;
    }

    /**
     * TODO в силу экономия времени без отдельного класс валидации
     */
    private function validate(): void
    {
        if (trim($this->user->firstName) === '' || trim($this->user->lastName) === '') {
            $this->errors['empty'] = 'First name and last name required!';
        }

        if ($this->more($this->user->firstName, 100)) {
            $this->errors['firstName'] = 'First name cannot be more than 100 symbols!';
        }

        if ($this->more($this->user->lastName, 100)) {
            $this->errors['lastName'] = 'Last name cannot be more than 100 symbols!';
        }

        if ( ! $this->user->email || ! filter_var($this->user->email,
            FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email invalid!';
        }

        if ($this->less($this->user->password, 5)) {
            $this->errors['password'] = 'Password must be at least 5 symbols! 8-16 - good practice';
        }
        if ($this->user->password !== $this->user->passwordConfirm) {
            $this->errors['same'] = 'Passwords must be same!';
        }
    }

    public function getInputFirstName(): string
    {
        return $this->user->firstName;
    }

    public function getInputLastName(): string
    {
        return $this->user->lastName;
    }

    public function getInputEmail(): string
    {
        return $this->user->email;
    }

}