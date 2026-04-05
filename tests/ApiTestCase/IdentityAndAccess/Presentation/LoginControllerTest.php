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

   public function testLoginSuccessWithEmail(): void
   {
      $email = 'test@example.com';

      $this->createUser($email, '+243820110123', 'password');

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => $email,
            'password' => 'password',
         ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('token', $data);
      $this->assertNotEmpty($data['token']);
   }

   public function testLoginSuccessWithPhone(): void
   {
      $phone = '+243820110123';

      $this->createUser('test@example.com', $phone, 'password');

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => $phone,
            'password' => 'password',
         ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('token', $data);
      $this->assertNotEmpty($data['token']);
   }

   public function testLoginSuccessWithPhoneWithoutPlus(): void
   {
      $phone = '+243820110123';

      $this->createUser('test@example.com', $phone, 'password');

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => '+243820110123', // Sans le + mais valide
            'password' => 'password',
         ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('token', $data);
      $this->assertNotEmpty($data['token']);
   }

   public function testLoginSuccessWithPhoneLocalFormat(): void
   {
      $phone = '+243820110123';

      $this->createUser('test@example.com', $phone, 'password');

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => '+243820110123',
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

      $this->createUser($email, '+243820110124');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => $email,
            'password' => 'wrongpassword',
         ],
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureWithPhoneWrongPassword(): void
   {
      $phone = '+243820110125';

      $this->createUser('test3@example.com', $phone, 'correctpassword');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => $phone,
            'password' => 'wrongpassword',
         ],
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureUserNotFoundByEmail(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => 'nonexistent@example.com',
            'password' => 'password123',
         ],
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureUserNotFoundByPhone(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identiant' => '+243999999999',
            'password' => 'password123',
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }


   public function testLoginMissingEmailOrPhone(): void
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
            'identifiant' => 'test@example.com',
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginWithInvalidPhoneFormat(): void
   {
      $this->createUser('test4@example.com', '+243820110126', 'password');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => '123',
            'password' => 'password',
         ],
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   private function createUser(string $email, string $phone): User
   {
      $user = UserFactory::createOne($email, $phone);

      $this->save($user);

      return $user;
   }
}
