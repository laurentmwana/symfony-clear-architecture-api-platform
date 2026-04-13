<?php

namespace App\SharedContext\Infrastructure\Persistance\Doctrine\Orm;

use Doctrine\ORM\EntityManagerInterface;

trait DoctrineRepositoryTrait
{

   public function save(object $entity): void
   {
      $this->getEntityManager()->persist($entity);
      $this->getEntityManager()->flush();
   }

   public function remove(object $entity): void
   {
      $this->getEntityManager()->remove($entity);
      $this->getEntityManager()->flush();
   }
}
