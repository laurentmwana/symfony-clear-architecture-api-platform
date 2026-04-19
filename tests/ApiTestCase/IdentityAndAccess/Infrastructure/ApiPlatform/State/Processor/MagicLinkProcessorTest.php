<?php

namespace App\Tests\ApiTestCase\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use App\IdentityAndAccess\Domain\Entity\MagicLink;
use App\IdentityAndAccess\Infrastructure\Persistance\Fixtures\MagicLinkFixtures;
use App\Tests\ApiTestCase\AbstractApiTestCase;
use Symfony\Component\String\ByteString;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class MagicLinkProcessorTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testSendMagicLinkSuccessWithEmail(): void
   {
      $email = 'test@example.com';
      $this->createUser($email, '+243820110123');

      $response = static::createClient()->request('POST', '/api/auth/magic-link', [
         'json' => [
            'email' => $email,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('status', $data);
      $this->assertEquals('success', $data['status']);
      $this->assertArrayHasKey('message', $data);
   }

   public function testSendMagicLinkUserNotFound(): void
   {
      $email = 'nonexistent@example.com';

      $response = static::createClient()->request('POST', '/api/auth/magic-link', [
         'json' => [
            'email' => $email,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(200);
   }

   public function testSendMagicLinkInvalidEmailFormat(): void
   {
      static::createClient()->request('POST', '/api/auth/magic-link', [
         'json' => [
            'email' => 'invalid-email',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testVerifyMagicLinkSuccess(): void
   {
      $email = 'test@example.com';
      $this->createUser($email, '+243820110123');

      $magicLink = $this->createMagicLink($email);
      $magicToken = $magicLink->getToken()->value();

      $response = static::createClient()->request('POST', '/api/auth/magic-link/verify', [
         'json' => [
            'token' => $magicToken,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();
      $this->assertArrayHasKey('token', $data);
      $this->assertNotEmpty($data['token']);
   }

   public function testVerifyMagicLinkInvalidToken(): void
   {
      static::createClient()->request('POST', '/api/auth/magic-link/verify', [
         'json' => [
            'token' => 'invalid-token-12345',
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testVerifyMagicLinkExpiredToken(): void
   {
      $email = 'test@example.com';
      $this->createUser($email, '+243820110123');

      $expiredMagicLink = $this->createExpiredMagicLink($email);
      $expiredToken = $expiredMagicLink->getToken()->value();

      static::createClient()->request('POST', '/api/auth/magic-link/verify', [
         'json' => [
            'token' => $expiredToken,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testVerifyMagicLinkMissingToken(): void
   {
      static::createClient()->request('POST', '/api/auth/magic-link/verify', [
         'json' => [],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(422);
   }

   public function testVerifyMagicLinkUserNotFound(): void
   {
      $email = 'test@example.com';
      $user = $this->createUser($email, '+243820110123');
      $magicLink = $this->createMagicLink($email);
      $magicToken = $magicLink->getToken()->value();

      $this->remove($user);

      static::createClient()->request('POST', '/api/auth/magic-link/verify', [
         'json' => [
            'token' => $magicToken,
         ],
         'headers' => $this->getHeadersContentJson(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   private function createMagicLink(string $email): MagicLink
   {
      $validToken = $this->generateValidToken();
      return $this->createAndSaveMagicLink($email, $validToken);
   }

   private function createExpiredMagicLink(string $email): MagicLink
   {
      $validToken = $this->generateValidToken();
      $magicLink = MagicLinkFixtures::createOneExpired($email, $validToken);
      $this->save($magicLink);
      return $magicLink;
   }

   private function createAndSaveMagicLink(string $email, string $token): MagicLink
   {
      $magicLink = MagicLinkFixtures::createOne($email, $token);
      $this->save($magicLink);
      return $magicLink;
   }

   private function generateValidToken(int $length = 64): string
   {
      return ByteString::fromRandom($length);
   }
}
