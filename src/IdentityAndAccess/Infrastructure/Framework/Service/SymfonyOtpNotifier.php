<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Service;

use App\IdentityAndAccess\Domain\Service\OtpNotifier;
use App\SharedContext\Domain\ValueObject\Email;
use App\OneTimePassword\Domain\Entity\OneTimePassword;
use App\SharedContext\Domain\ValueObject\Phone;

class SymfonyOtpNotifier implements OtpNotifier
{
   public function sendToEmail(Email $email, OneTimePassword $otp): void
   {
      throw new \Exception('Not implemented');
   }

   public function sendToPhone(Phone $phone, OneTimePassword $otp): void
   {
      throw new \Exception('Not implemented');
   }
}
