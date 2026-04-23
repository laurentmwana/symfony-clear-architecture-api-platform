<?php

namespace App\IdentityAndAccess\Domain\Entity;

use App\IdentityAndAccess\Domain\Enums\OtpStatusEnum;
use App\IdentityAndAccess\Domain\ValueObject\OtpStatus;
use App\IdentityAndAccess\Domain\ValueObject\OtpCode;
use App\SharedContext\Domain\ValueObject\Attempts;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use App\SharedContext\Domain\ValueObject\Uuid;
use DateTimeImmutable;

class OneTimePassword
{
   private string $id;
   private string $userId;
   private string $code;
   private string $status;
   private DateTimeImmutable $expiresAt;
   private DateTimeImmutable $createdAt;
   private DateTimeImmutable $updatedAt;
   private ?DateTimeImmutable $usedAt = null;
   private int $attempts;
   private ?string $ipAddress;
   private ?string $userAgent;

   private function __construct(
      Uuid $id,
      Uuid $userId,
      OtpCode $code,
      OtpStatus $status,
      DateTimeImmutable $expiresAt,
      Attempts $attempts,
      ?IpAddress $ipAddress,
      ?UserAgent $userAgent
   ) {
      $this->id = $id;
      $this->userId = $userId;
      $this->code = $code;
      $this->status = $status;
      $this->expiresAt = $expiresAt;
      $this->createdAt = new DateTimeImmutable();
      $this->updatedAt = new DateTimeImmutable();
      $this->attempts = $attempts->value();
      $this->ipAddress = $ipAddress;
      $this->userAgent = $userAgent;
   }

   public static function create(
      Uuid $id,
      Uuid $userId,
      OtpCode $code,
      ?IpAddress $ipAddress = null,
      ?UserAgent $userAgent = null,
      ?DateTimeImmutable $expiresAt = null,
   ): self {
      return new self(
         $id,
         $userId,
         $code,
         new OtpStatus(OtpStatusEnum::PENDING),
         $expiresAt ?? new DateTimeImmutable('+10 minutes'),
         new Attempts(0),
         $ipAddress,
         $userAgent
      );
   }

   public function markAsUsed(): void
   {
      $this->status = new OtpStatus(OtpStatusEnum::USED);
      $this->usedAt = new DateTimeImmutable();
      $this->updatedAt = new DateTimeImmutable();
   }

   public function isUsed(): bool
   {
      return $this->status->value() === OtpStatusEnum::USED
         && $this->usedAt !== null;
   }

   public function isExpired(): bool
   {
      return $this->expiresAt < new DateTimeImmutable();
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

   public function getUserId()
   {
      return $this->userId;
   }

   public function getCode()
   {
      return $this->code;
   }
}
