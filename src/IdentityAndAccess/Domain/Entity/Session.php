<?php

namespace App\IdentityAndAccess\Domain\Entity;

use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use App\SharedContext\Domain\ValueObject\Uuid;
use DateTimeImmutable;

class Session
{
   private string $id;
   private string $userId;
   private ?string $userAgent = null;
   private ?string $ipAddress = null;
   private ?DateTimeImmutable $createdAt = null;

   public function __construct(
      Uuid $id,
      Uuid $userId,
      ?UserAgent $userAgent = null,
      ?IpAddress $ipAddress = null,
      ?DateTimeImmutable $createdAt = null
   ) {
      $this->id = (string) $id;
      $this->userId = (string) $userId;
      $this->userAgent = (string) $userAgent;
      $this->ipAddress = (string) $ipAddress;
      $this->createdAt = $createdAt ?? new DateTimeImmutable();
   }

   public static function  create(
      Uuid $id,
      Uuid $userId,
      ?UserAgent $userAgent = null,
      ?IpAddress $ipAddress = null,
   ): self {
      return new self($id, $userId, $userAgent, $ipAddress);
   }

   public function getId(): Uuid
   {
      return new Uuid($this->id);
   }

   public function getUserId(): Uuid
   {
      return new Uuid($this->userId);
   }

   public function getUserAgent(): ?UserAgent
   {
      return new UserAgent($this->userAgent);
   }

   public function getIpAddress(): ?IpAddress
   {
      return new IpAddress($this->ipAddress);
   }

   public function getCreatedAt(): ?DateTimeImmutable
   {
      return $this->createdAt;
   }
}
