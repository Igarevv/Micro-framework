<?php

namespace App\Services;

use App\DTO\User;
use App\Exceptions\UserException;
use App\Repository\Interfaces\UserRepositoryInterface;

class AuthService
{

    private \stdClass $user;

    public function __construct(
      protected UserRepositoryInterface $repository
    ) {}

    public function saveToDb(User $user): User
    {
        $userExists = $this->repository->findByEmail($user->email());

        if ($userExists !== false){
            throw new UserException('User with this email is already exists!');
        }

        $result = $this->repository->save($user);

        if ($result){
            $user->setUserId($result);
        }

        return $user;
    }

    public function authenticate(array $inputData): bool
    {
        $userFromDb = $this->repository->findByEmail($inputData['email']);

        if (! $userFromDb){
            return false;
        }

        if (password_verify($inputData['password'], $userFromDb->password())){
            $this->user = $this->presentUser($userFromDb);

            return true;
        }

        return false;
    }

    public function presentUser(User $user): \stdClass
    {
        $presetUser = new \stdClass();
        $presetUser->first_name = $user->firstName();
        $presetUser->last_name  = $user->lastName();
        $presetUser->id   = $user->id();
        $presetUser->email = $user->email();
        $presetUser->createdAt = $user->createdAt()->format('Y-m-d H:s');

        return $presetUser;
    }

    public function getUser(): \stdClass|false
    {
        return $this->user;
    }

}