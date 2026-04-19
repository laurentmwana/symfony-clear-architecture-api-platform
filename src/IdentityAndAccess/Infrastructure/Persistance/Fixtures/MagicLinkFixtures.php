<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Fixtures;

use App\IdentityAndAccess\Domain\Entity\MagicLink;
use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MagicLinkFixtures extends Fixture
{
   public function load(ObjectManager $manager): void
   {
      $magicLinks = $this->createMany(10);

      foreach ($magicLinks as $magicLink) {
         $manager->persist($magicLink);
      }

      $manager->flush();
   }

   public static function createOne(?string $email = null, ?string $token = null): MagicLink
   {
      $faker = Factory::create();

      if (null === $token) {
         $token = self::generateValidToken();
      }

      $emailValue = $email ?? $faker->email();
      $magicToken = new MagicLinkToken($token);
      $uuid = new Uuid($faker->uuid());
      $emailVO = new Email($emailValue);

      $expiresAt = new DateTimeImmutable('+1 hour');

      return MagicLink::create($uuid, $emailVO, $magicToken, expiresAt: $expiresAt);
   }

   public static function createOneExpired(?string $email = null, ?string $token = null): MagicLink
   {
      $faker = Factory::create();

      if (null === $token) {
         $token = self::generateValidToken();
      }

      $emailValue = $email ?? $faker->email();
      $magicToken = new MagicLinkToken($token);
      $uuid = new Uuid($faker->uuid());
      $emailVO = new Email($emailValue);

      $expiresAt = new DateTimeImmutable('-1 day');

      return MagicLink::create($uuid, $emailVO, $magicToken, expiresAt: $expiresAt);
   }

   /**
    * Crée plusieurs MagicLinks valides
    */
   public function createMany(int $count): array
   {
      $magicLinks = [];
      for ($i = 0; $i < $count; $i++) {
         $magicLinks[] = self::createOne();
      }
      return $magicLinks;
   }

   public function createManyExpired(int $count): array
   {
      $magicLinks = [];
      for ($i = 0; $i < $count; $i++) {
         $magicLinks[] = self::createOneExpired();
      }
      return $magicLinks;
   }

   private static function generateValidToken(): string
   {
      if (function_exists('random_bytes')) {
         return bin2hex(random_bytes(32));
      }

      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';

      for ($i = 0; $i < 64; $i++) {
         $randomString .= $characters[random_int(0, $charactersLength - 1)];
      }

      return $randomString;
   }

   public static function isValidToken(string $token): bool
   {
      return preg_match('/^[a-zA-Z0-9]{64}$/', $token) === 1;
   }
}
