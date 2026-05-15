<?php

namespace App\IdentityAndAccess\Infrastructure\Persistence\Doctrine\Orm;

use App\IdentityAndAccess\Domain\Entity\Session;
use App\IdentityAndAccess\Domain\Repository\SessionRepository;
use App\SharedContext\Domain\ValueObject\Uuid;
use App\SharedContext\Infrastructure\Persistence\Doctrine\Orm\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 */
class DoctrineSessionRepository extends ServiceEntityRepository implements SessionRepository
{

   use DoctrineRepositoryTrait;

   public function __construct(ManagerRegistry $registry)
   {
      parent::__construct($registry, Session::class);
   }

   public function findByUserId(Uuid $userId): ?Session
   {
      return $this->findOneBy([
         'userId' => $userId->value()
      ]);
   }

   public function findAllByUserId(Uuid $userId): array
   {
      return $this->findBy([
         'userId' => $userId->value()
      ]);
   }
}
