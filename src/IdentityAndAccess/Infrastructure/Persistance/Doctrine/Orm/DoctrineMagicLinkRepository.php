<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Doctrine\Orm;

use App\IdentityAndAccess\Domain\Entity\MagicLink;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\Repository\MagicLinkRepository;
use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Infrastructure\Persistance\Doctrine\Orm\DoctrineRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineMagicLinkRepository extends ServiceEntityRepository implements MagicLinkRepository
{
   use DoctrineRepositoryTrait;

   public function __construct(ManagerRegistry $registry)
   {
      return parent::__construct($registry, MagicLink::class);
   }

   public function findValidByEmail(Email $email): ?MagicLink
   {
      return $this->createQueryBuilder('ml')
         ->andWhere('ml.email = :email')
         ->andWhere('ml.expiresAt > :now')
         ->andWhere('ml.usedAt IS NULL')
         ->setParameter('email', $email->value())
         ->setParameter('now', new \DateTimeImmutable())
         ->orderBy('ml.createdAt', 'DESC')
         ->setMaxResults(1)
         ->getQuery()
         ->getOneOrNullResult();
   }

   public function findByToken(MagicLinkToken $token): ?MagicLink
   {
      return $this->findOneBy([
         'token' => $token->value(),
      ]);
   }
}
