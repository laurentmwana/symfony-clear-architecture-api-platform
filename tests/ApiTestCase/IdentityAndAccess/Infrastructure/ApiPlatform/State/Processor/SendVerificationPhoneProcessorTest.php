<?php

namespace App\Tests\ApiTestCase\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use App\Tests\ApiTestCase\AbstractApiTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class SendVerificationPhoneProcessorTest extends AbstractApiTestCase
{
   use ResetDatabase, Factories;

   protected function setUp(): void
   {
      parent::setUp();
   }

   public function testSendVerificationSuccess(): void
   {
      $phone = '+243820110123';

      $this->createUser(
         'test@example.com',
         $phone,
         phoneUnverified: true
      );

      $token = $this->getToken([
         'identifier' => $phone,
         'password' => 'password',
      ]);

      $response = static::createClient()->request('POST', '/api/auth/phone/send-verification', [
         'json' => [],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();

      $this->assertArrayHasKey('message', $data);
      $this->assertArrayHasKey('expires_minutes', $data);
      $this->assertArrayHasKey('attempts', $data);

      $this->assertNotEmpty($data['message']);
      $this->assertNotEmpty($data['expires_minutes']);
      $this->assertNotEmpty($data['attempts']);
   }

   public function testSendVerificationWhenPhoneAlreadyVerified(): void
   {
      $phone = '+243820110123';

      $this->createUser(
         'test@example.com',
         $phone,
         phoneUnverified: false
      );

      $token = $this->getToken([
         'identifier' => $phone,
         'password' => 'password',
      ]);

      $response = static::createClient()->request('POST', '/api/auth/phone/send-verification', [
         'json' => [],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => "Bearer $token",
         ],
      ]);

      $this->assertResponseIsSuccessful();
      $this->assertResponseStatusCodeSame(200);

      $data = $response->toArray();

      $this->assertArrayHasKey('message', $data);
      $this->assertNotEmpty($data['message']);
   }

   public function testSendVerificationFailsWhenNotAuthenticated(): void
   {
      static::createClient()->request('POST', '/api/auth/phone/send-verification', [
         'json' => [],
         'headers' => $this->getHeadersAccept(),
      ]);

      $this->assertResponseStatusCodeSame(401);
   }

   public function testSendVerificationFailsWithInvalidToken(): void
   {
      static::createClient()->request('POST', '/api/auth/phone/send-verification', [
         'json' => [],
         'headers' => [
            ...$this->getHeadersAccept(),
            'Authorization' => 'Bearer invalid-token',
         ],
      ]);

      $this->assertResponseStatusCodeSame(401);
   }
}
