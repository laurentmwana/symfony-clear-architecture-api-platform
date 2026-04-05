<?php

namespace App\Tests\ApiTestCase;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractApiTestCase extends ApiTestCase
{
   private ?string $token = null;

   protected static ?bool $alwaysBootKernel = true;

   private EntityManagerInterface $entityManager;

   protected function setUp(): void
   {
      parent::setUp();

      self::bootKernel();

      $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
   }

   public function getManager(): EntityManagerInterface
   {
      return $this->entityManager;
   }


   protected function createClientWithCredentials($token = null): Client
   {
      $token = $token ?: $this->getToken();

      return static::createClient([], ['headers' => ['authorization' => 'Bearer ' . $token]]);
   }

   protected function getToken($body = []): string
   {
      if ($this->token) {
         return $this->token;
      }

      $response = static::createClient()->request('POST', '/login', ['json' => $body ?: [
         'username' => 'admin@example.com',
         'password' => '$3cr3t',
      ]]);

      $this->assertResponseIsSuccessful();
      $data = $response->toArray();
      $this->token = $data['token'];

      return $data['token'];
   }

   public function save(object $object): object
   {
      $this->getManager()->persist($object);
      $this->getManager()->flush();

      return $object;
   }

   public function remove(object $object): object
   {
      $this->getManager()->remove($object);
      $this->getManager()->flush();

      return $object;
   }
}
