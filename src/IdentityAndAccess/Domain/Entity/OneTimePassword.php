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

   private ?string $ipAddress = null;
   private ?string $userAgent = null;

   private function __construct(
      Uuid $id,
      Uuid $userId,
      OtpCode $code,
      OtpStatus $status,
      DateTimeImmutable $expiresAt,
      Attempts $attempts,
      ?IpAddress $ipAddress,
      ?UserAgent $userAgent,
      ?DateTimeImmutable $createdAt = null,
      ?DateTimeImmutable $updatedAt = null,
   ) {
      $this->id = (string) $id;
      $this->userId = (string) $userId;
      $this->code = (string) $code;
      $this->status = (string) $status;

      $this->expiresAt = $expiresAt;
      $this->attempts = $attempts->value();

      $this->ipAddress = $ipAddress?->value();
      $this->userAgent = $userAgent?->value();

      $this->createdAt = $createdAt ?? new DateTimeImmutable();
      $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
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
      $this->status = OtpStatusEnum::USED->value;
      $this->usedAt = new DateTimeImmutable();
      $this->updatedAt = new DateTimeImmutable();
   }

   public function isUsed(): bool
   {
      return $this->status === OtpStatusEnum::USED->value
         && $this->usedAt !== null;
   }

   public function isExpired(): bool
   {
      return $this->expiresAt < new DateTimeImmutable();
   }

   public function getId(): Uuid
   {
      return new Uuid($this->id);
   }

   public function getUserId(): Uuid
   {
      return new Uuid($this->userId);
   }

   public function getCode(): OtpCode
   {
      return new OtpCode($this->code);
   }

   public function getStatus(): OtpStatus
   {
      return new OtpStatus(OtpStatusEnum::from($this->status));
   }

   public function getAttempts(): Attempts
   {
      return new Attempts($this->attempts);
   }

   public function getIpAddress(): ?IpAddress
   {
      return $this->ipAddress ? new IpAddress($this->ipAddress) : null;
   }

   public function getUserAgent(): ?UserAgent
   {
      return $this->userAgent ? new UserAgent($this->userAgent) : null;
   }

   public function getExpiresAt(): DateTimeImmutable
   {
      return $this->expiresAt;
   }

   public function getCreatedAt(): DateTimeImmutable
   {
      return $this->createdAt;
   }

   public function getUpdatedAt(): DateTimeImmutable
   {
      return $this->updatedAt;
   }
}
