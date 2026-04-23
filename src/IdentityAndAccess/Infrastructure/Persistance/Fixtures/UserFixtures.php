<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Fixtures;

use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Name;
use App\SharedContext\Domain\ValueObject\Uuid;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\SharedContext\Domain\ValueObject\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
   private const PASSWORD_DEFAULT = '$2y$13$crNg58xtI2iQwCnVqjmZS.5q4LI9/SGLfYu8hU.pme2/j2DemE6jW';

   private const PHONE_PREFIXES = [
      '24380',
      '24381',
      '24382',
      '24383',
      '24384',
      '24385',
      '24386',
      '24387',
      '24388',
      '24389',
      '24390',
      '24391',
      '24397',
      '24398',
      '24399'
   ];

   public function load(ObjectManager $manager): void
   {
      $users = self::createMany(10);

      foreach ($users as $user) {
         $manager->persist($user);
      }

      $manager->flush();
   }

   public static function createOne(?string $email = null, ?string $phone = null): User
   {
      $faker = Factory::create();

      $name = new Name($faker->name());
      $email = new Email($email ?? $faker->email());
      $phone = new Phone($phone ?? self::generateCongolesePhoneNumber());
      $password = Password::fromPlainUnhashed(self::PASSWORD_DEFAULT);
      $uuid = new Uuid($faker->uuid());

      return User::create($uuid, $name, $email, $phone, $password);
   }

   /**
    * @param integer $count
    * @return User[]
    */
   public static function createMany(int $count): array
   {
      $users = [];
      for ($i = 0; $i < $count; $i++) {
         $users[] = self::createOne();
      }
      return $users;
   }

   private static function generateCongolesePhoneNumber(): string
   {
      $prefix = self::PHONE_PREFIXES[array_rand(self::PHONE_PREFIXES)];

      $number = '';
      for ($i = 0; $i < 7; $i++) {
         $number .= random_int(0, 9);
      }

      return '+' . $prefix . $number;
   }
}
