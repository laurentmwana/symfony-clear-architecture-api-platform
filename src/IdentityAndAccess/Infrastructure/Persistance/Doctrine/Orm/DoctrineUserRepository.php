<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Doctrine\Orm;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineUserRepository extends ServiceEntityRepository implements UserRepository
{
   public function __construct(ManagerRegistry $registry)
   {
      return parent::__construct($registry, User::class);
   }

   public function findByEmail(Email $email): ?User
   {
      return $this->findOneBy(['email' => $email->value()]);
   }
}
