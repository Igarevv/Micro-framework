<?php

namespace App\Repository;

use App\DTO\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Mappers\UserMapper;

class UserRepository extends AbstractRepository implements
  UserRepositoryInterface
{

    public function __construct(
      private UserMapper $mapper
    ) {}

    public function save(User $user): int
    {
        $builder = $this->db()->createQueryBuilder();

        $builder->insert('book_user')
          ->values([
            'first_name' => ':first_name',
            'last_name' => ':last_name',
            'password' => ':password',
            'email' => ':email',
          ])
          ->setParameters([
            'first_name' => $user->firstName(),
            'last_name' => $user->lastName(),
            'password' => $user->password(),
            'email' => $user->email(),
          ]);
        $builder->executeQuery();

        return $this->db()->lastInsertId();
    }

    public function findById(int $id): User|false
    {
        $builder = $this->db()->createQueryBuilder();

        $builder->select('id', 'first_name', 'last_name', 'email', 'created_at')
          ->from('book_user')
          ->where('id = ?')
          ->setParameter(0, $id)
          ->executeQuery();

        $data = $builder->fetchAssociative() ?? [];

        return $data ? $this->mapper->convertDataToUser($data) : false;
    }

    public function findByEmail(string $email): User|false
    {
        $builder = $this->db()->createQueryBuilder();

        $builder->select(
          'id',
          'email',
          'last_name',
          'first_name',
          'email',
          'created_at',
          'password')
          ->from('book_user')
          ->where('email = ?')
          ->setParameter(0, $email)
          ->executeQuery();

        $data = $builder->fetchAssociative() ?? [];

        return $data ? $this->mapper->convertDataToUser($data) : false;
    }

}