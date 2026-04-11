<?php

namespace App\OneTimePassword\Infrastructure\Persistance\Doctrine\Orm;

use App\OneTimePassword\Domain\Entity\OneTimePassword;
use App\OneTimePassword\Domain\Repository\OneTimePasswordRepository;
use App\OneTimePassword\Domain\ValueObject\OtpPassword;
use App\SharedContext\Domain\ValueObject\Uuid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineOneTimePasswordRepository extends ServiceEntityRepository implements OneTimePasswordRepository
{
   public function __construct(ManagerRegistry $registry)
   {
      return parent::__construct($registry, OneTimePassword::class);
   }

   public function findByUserId(Uuid $userId): ?OneTimePassword
   {
      return $this->findOneBy(['userId' => $userId->value()]);
   }

   public function findOtpForUser(OtpPassword $otp, Uuid $userId): ?OneTimePassword
   {
      return $this->findOneBy([
         'userId' => $userId->value(),
         'otpCode' => $otp->value(),
      ]);
   }

   public function create(OneTimePassword $oneTimePassword): bool
   {
      /** @var OneTimePassword|null */
      $result = $this->createQueryBuilder('o')
         ->set('o.expiresAt', ':now')
         ->where('o.userId = :userId')
         ->andWhere('o.expiresAt IS NULL OR o.expiresAt > :now')
         ->setParameter('userId', $oneTimePassword->userId()->value())
         ->setParameter('now', new \DateTimeImmutable())
         ->getQuery()
         ->getOneOrNullResult();

      $manager = $this->getEntityManager();

      if (null !== $result) {
         $result->markAsUsed();
         $manager->persist($result);
         $manager->flush();
      }

      $manager->persist($oneTimePassword);
      $manager->flush();

      return true;
   }
}
