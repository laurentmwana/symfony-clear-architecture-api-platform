<?php

namespace App\OneTimePassword\Infrastructure\Framework\Service;

use App\OneTimePassword\Domain\Service\OtpGenerator;
use App\OneTimePassword\Domain\ValueObject\OtpPassword;
use DateTimeImmutable;

class SymfonyOtpGenerator implements OtpGenerator
{
   public function generate(): OtpPassword
   {
      $otp = random_int(100000, 999999);
      return new OtpPassword((string)$otp);
   }

   public function expiresAt(int $expiresIn = 180): DateTimeImmutable
   {
      return (new DateTimeImmutable())->modify("+{$expiresIn} seconds");
   }

   public function isExpired(DateTimeImmutable $expiresAt): bool
   {
      return $expiresAt <= new DateTimeImmutable();
   }
}
