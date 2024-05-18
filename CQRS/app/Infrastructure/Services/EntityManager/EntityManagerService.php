<?php

namespace App\Infrastructure\Services\EntityManager;

use App\Infrastructure\Services\EntityManager\Contracts\DatabaseEntity;
use App\Infrastructure\Services\EntityManager\Contracts\EntityManagerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

/**
 * @mixin EntityManagerInterface
 */
class EntityManagerService implements EntityManagerServiceInterface
{

    public function __construct(
      protected EntityManagerInterface $entityManager
    ) {}

    public function sync(DatabaseEntity $entity = null): void
    {
        if ($entity){
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function delete(DatabaseEntity $entity, bool $sync = false): void
    {
        $this->entityManager->remove($entity);

        if ($sync){
            $this->sync();
        }
    }

    public function clear(?string $entityName = null): void
    {
        if ($entityName === null){
            $this->entityManager->clear();
        }

        $unitOfWork = $this->entityManager->getUnitOfWork();
        $entities = $unitOfWork->getIdentityMap()[$entityName] ?? [];

        foreach ($entities as $entity){
            $this->entityManager->detach($entity);
        }
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this->entityManager, $name)){
            return call_user_func_array([$this->entityManager, $name], $arguments);
        }
        throw new RuntimeException('Call to undefined method' . $name);
    }

}