<?php

namespace App\Tests\ApiTestCase\IdentityAndAccess\Presentation;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Infrastructure\Persistance\Factories\UserFactory;
use App\Tests\ApiTestCase\AbstractApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class LoginControllerTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testLoginSuccess(): void
   {
      $email = 'test@example.com';

      $this->createUser($email);

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'email' => $email,
            'password' => 'password',
         ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('token', $data);
      $this->assertNotEmpty($data['token']);
   }

   public function testLoginFailureWrongPassword(): void
   {
      $email = 'test2@example.com';
      $password = 'correctpassword';

      $this->createUser($email, $password);

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'email' => $email,
            'password' => 'wrongpassword',
         ],
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function test_loginFailure_user_not_found(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
         ],
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginMissingEmail(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'password' => 'password123',
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginMissingPassword(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'email' => 'test@example.com',
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   private function createUser(string $email): User
   {
      $user = UserFactory::createOne($email);

      $this->save($user);

      return $user;
   }
}
