<?php

namespace App\OneTimePassword\Domain\Service;

use App\OneTimePassword\Domain\ValueObject\OtpPassword;
use DateTimeImmutable;

interface OtpGenerator
{
   public function generate(): OtpPassword;

   public function expiresAt(int $expiresIn = 180): DateTimeImmutable;

   public function isExpired(DateTimeImmutable $expiresAt): bool;
}
