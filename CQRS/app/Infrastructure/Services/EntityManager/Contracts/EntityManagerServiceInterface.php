<?php

namespace App\Infrastructure\Services\EntityManager\Contracts;

/**
 * @mixin \Doctrine\ORM\EntityManagerInterface
 */
interface EntityManagerServiceInterface
{
    public function __call(string $name, array $arguments);

    public function sync(DatabaseEntity $entity = null): void;

    public function delete(DatabaseEntity $entity, bool $sync = false): void;

    public function clear(?string $entityName = null): void;
}