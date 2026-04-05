<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Doctrine\Orm;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\SharedContext\Domain\ValueObject\Phone;
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

   public function findByEmailOrPhone(Email|Phone $identifiant): ?User
   {
      $value = $identifiant->value();

      return $this->createQueryBuilder('u')
         ->where('u.email = :email OR u.phone = :phone')
         ->setParameter('email', $value)
         ->setParameter('phone', $value)
         ->getQuery()
         ->getOneOrNullResult();
   }
}
