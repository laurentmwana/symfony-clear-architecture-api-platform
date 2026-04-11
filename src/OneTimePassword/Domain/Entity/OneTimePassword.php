<?php

namespace App\OneTimePassword\Domain\Entity;

use App\OneTimePassword\Domain\ValueObject\OtpPassword;
use App\SharedContext\Domain\ValueObject\Uuid;
use DateTimeImmutable;

final class OneTimePassword
{
   private readonly Uuid $id;
   private readonly Uuid $userId;
   private readonly OtpPassword $otpCode;
   private readonly DateTimeImmutable $createdAt;
   private ?DateTimeImmutable $expiresAt = null;
   private ?DateTimeImmutable $updatedAt = null;

   public function __construct(
      Uuid $id,
      Uuid $userId,
      OtpPassword $otpCode,
      ?DateTimeImmutable $expiresAt = null,
      ?DateTimeImmutable $createdAt = null,
      ?DateTimeImmutable $updatedAt = null
   ) {
      $this->id = $id;
      $this->userId = $userId;
      $this->otpCode = $otpCode;
      $this->expiresAt = $expiresAt;
      $this->createdAt = $createdAt ?? new DateTimeImmutable();
      $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
   }

   public static function create(Uuid $id, Uuid $userId, OtpPassword $otpCode, ?DateTimeImmutable $expiresAt = null): self
   {
      return new self($id, $userId, $otpCode, $expiresAt);
   }

   public function id(): Uuid
   {
      return $this->id;
   }

   public function userId(): Uuid
   {
      return $this->userId;
   }

   public function otpCode(): OtpPassword
   {
      return $this->otpCode;
   }

   public function expiresAt(): ?DateTimeImmutable
   {
      return $this->expiresAt;
   }

   public function isExpired(): bool
   {
      return $this->expiresAt !== null && $this->expiresAt <= new DateTimeImmutable();
   }

   public function markAsUsed(): void
   {
      $this->updatedAt = new DateTimeImmutable();
      $this->expiresAt = new DateTimeImmutable();
   }

   public function createdAt(): DateTimeImmutable
   {
      return $this->createdAt;
   }

   public function updatedAt(): ?DateTimeImmutable
   {
      return $this->updatedAt;
   }
}
