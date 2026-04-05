<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Factories;

use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Name;
use App\SharedContext\Domain\ValueObject\Uuid;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFactory extends Fixture
{
   private const PASSWORD_DEFAULT = '$2y$13$crNg58xtI2iQwCnVqjmZS.5q4LI9/SGLfYu8hU.pme2/j2DemE6jW';

   public function load(ObjectManager $manager): void
   {
      self::createMany(10);

      $manager->flush();
   }

   public static function createOne(?string $email = null, ?string $password = null): User
   {
      $faker = Factory::create();

      $name = new Name($faker->name());
      $email = new Email($email ?? $faker->email());
      $password = Password::fromHash($password ?? self::PASSWORD_DEFAULT);
      $uuid = new Uuid($faker->uuid());

      return User::create($uuid, $name, $email, $password);
   }

   public static function createMany(int $count): array
   {
      $users = [];
      for ($i = 0; $i < $count; $i++) {
         $users[] = self::createOne();
      }
      return $users;
   }
}
