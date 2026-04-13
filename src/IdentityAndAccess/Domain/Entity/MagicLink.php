<?php

namespace App\IdentityAndAccess\Domain\Entity;

use App\IdentityAndAccess\Domain\Enums\MagicLinkStatusEnum;
use App\IdentityAndAccess\Domain\ValueObject\MagicLinkStatus;
use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;
use App\SharedContext\Domain\ValueObject\Attempts;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use App\SharedContext\Domain\ValueObject\Uuid;
use DateTimeImmutable;

class MagicLink
{
   private Uuid $id;
   private Email $email;
   private MagicLinkToken $token;
   private MagicLinkStatus $status;
   private DateTimeImmutable $expiresAt;
   private DateTimeImmutable $createdAt;
   private DateTimeImmutable $updatedAt;
   private ?DateTimeImmutable $usedAt = null;
   private Attempts $attempts;
   private ?IpAddress $ipAddress;
   private ?UserAgent $userAgent;

   private function __construct(
      Uuid $id,
      Email $email,
      MagicLinkToken $token,
      MagicLinkStatus $status,
      DateTimeImmutable $expiresAt,
      Attempts $attempts,
      ?IpAddress $ipAddress,
      ?UserAgent $userAgent
   ) {
      $this->id = $id;
      $this->email = $email;
      $this->token = $token;
      $this->status = $status;
      $this->expiresAt = $expiresAt;
      $this->createdAt = new DateTimeImmutable();
      $this->updatedAt = new DateTimeImmutable();
      $this->attempts = $attempts;
      $this->ipAddress = $ipAddress;
      $this->userAgent = $userAgent;
   }

   public static function create(
      Uuid $id,
      Email $email,
      MagicLinkToken $token,
      ?IpAddress $ipAddress = null,
      ?UserAgent $userAgent = null,
      ?DateTimeImmutable $expiresAt = null,
   ): self {
      return new self(
         $id,
         $email,
         $token,
         new MagicLinkStatus(MagicLinkStatusEnum::PENDING),
         $expiresAt ?? new DateTimeImmutable('+10 minutes'),
         new Attempts(0),
         $ipAddress,
         $userAgent
      );
   }

   public function markAsUsed(): void
   {
      $this->status = new MagicLinkStatus(MagicLinkStatusEnum::USED);
      $this->usedAt = new DateTimeImmutable();
      $this->updatedAt = new DateTimeImmutable();
   }

   public function isUsed(): bool
   {
      return $this->status->value() === MagicLinkStatusEnum::USED
         && $this->usedAt !== null;
   }

   public function isExpired(): bool
   {
      return $this->expiresAt < new DateTimeImmutable();
   }

   public function getToken()
   {
      return $this->token;
   }

   public function getEmail()
   {
      return $this->email;
   }

   public function getId()
   {
      return $this->id;
   }

   public function getAttempts()
   {
      return $this->attempts;
   }

   public function getIpAddress()
   {
      return $this->ipAddress;
   }

   public function getUserAgent()
   {
      return $this->userAgent;
   }

   public function getExpiresAt()
   {
      return $this->expiresAt;
   }
}
