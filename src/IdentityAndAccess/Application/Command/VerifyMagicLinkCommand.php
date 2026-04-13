<?php

namespace App\IdentityAndAccess\Application\Command;

use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;

class VerifyMagicLinkCommand
{
   public function __construct(private MagicLinkToken $token) {}

   public function getToken()
   {
      return $this->token;
   }
}
