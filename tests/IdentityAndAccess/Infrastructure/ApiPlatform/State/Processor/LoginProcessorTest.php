<?php

namespace App\Tests\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Infrastructure\Persistance\Factories\UserFactory;
use App\Tests\ApiTestCase\AbstractApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class LoginProcessorTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testLoginSuccessWithEmail(): void
   {
      $email = 'test@example.com';

      $this->createUser($email, '+243820110123');

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => $email,
            'password' => 'password',
         ],
         'headers' => $this->getHeadersJson(),
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

      $this->createUser('test@example.com', $phone);

      $response = static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => $phone,
            'password' => 'password',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('token', $data);
      $this->assertNotEmpty($data['token']);
   }

   public function testLoginWithPhoneWithoutPlusShouldFail(): void
   {
      $phone = '+243820110123';
      $plainPassword = 'password123456';

      $this->createUser('test@example.com', $phone, $plainPassword);

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => '243820110123', // Missing +
            'password' => $plainPassword,
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      // Returns 422 because phone format requires +243 prefix
      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginWithPhoneLocalFormatShouldFail(): void
   {
      $phone = '+243820110123';
      $plainPassword = 'password123456';

      $this->createUser('test@example.com', $phone, $plainPassword);

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => '0820110123', // Local format
            'password' => $plainPassword,
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      // Returns 422 because phone format requires +243 prefix
      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginFailureWrongPassword(): void
   {
      $email = 'test2@example.com';
      $plainPassword = 'password123456';

      $this->createUser($email, '+243820110124', $plainPassword);

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => $email,
            'password' => 'wrongpassword',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureWithPhoneWrongPassword(): void
   {
      $phone = '+243820110125';
      $plainPassword = 'correctpassword123';

      $this->createUser('test3@example.com', $phone, $plainPassword);

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => $phone,
            'password' => 'wrongpassword',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureUserNotFoundByEmail(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => 'nonexistent@example.com',
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginFailureUserNotFoundByPhone(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => '+243999999999',
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testLoginMissingIdentifier(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginMissingPassword(): void
   {
      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => 'test@example.com',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginWithInvalidPhoneFormat(): void
   {
      $this->createUser('test4@example.com', '+243820110126', 'password123456');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => '123', // Invalid format
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginWithInvalidEmailFormat(): void
   {
      $this->createUser('test5@example.com', '+243820110127', 'password123456');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => 'invalid-email', // Invalid email format
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testLoginWithWrongPhonePrefix(): void
   {
      $this->createUser('test6@example.com', '+243820110128', 'password123456');

      static::createClient()->request('POST', '/api/auth/login', [
         'json' => [
            'identifiant' => '+123456789012', // Wrong prefix
            'password' => 'password123456',
         ],
         'headers' => $this->getHeadersJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   private function createUser(?string $email = null, ?string $phone = null): User
   {
      $user = UserFactory::createOne($email, $phone);

      $this->save($user);

      return $user;
   }
}
