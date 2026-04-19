<?php

namespace App\Tests\ApiTestCase;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Infrastructure\Persistance\Fixtures\UserFixtures;
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

   protected function getHeadersContentJson()
   {
      return [
         'accept' => 'application/json',
         'Content-Type' => 'application/json'
      ];
   }

   protected function getHeadersAccept()
   {
      return [
         'accept' => 'application/json',
      ];
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

   protected function clearRateLimitCache(): void
   {
      $cachePool = static::getContainer()->get('cache.rate_limiter');
      $cachePool->clear();
   }

   public function createUser(?string $email = null, ?string $phone = null): User
   {
      $user = UserFixtures::createOne($email, $phone);
      $this->save($user);
      return $user;
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
