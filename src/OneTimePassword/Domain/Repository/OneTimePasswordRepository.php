<?php

namespace App\OneTimePassword\Domain\Repository;

use App\OneTimePassword\Domain\Entity\OneTimePassword;
use App\OneTimePassword\Domain\ValueObject\OtpPassword;
use App\SharedContext\Domain\ValueObject\Uuid;

interface OneTimePasswordRepository
{
   public function findByUserId(Uuid $userId): ?OneTimePassword;
   public function findOtpForUser(OtpPassword $otp, Uuid $userId): ?OneTimePassword;
   public function create(OneTimePassword $oneTimePassword): bool;
}
