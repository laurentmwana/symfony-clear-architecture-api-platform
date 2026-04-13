<?php

namespace App\IdentityAndAccess\Application\Command;

use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;

class SendMagicLinkCommand
{
   public function __construct(
      private Email $email,
      private IpAddress $ipAddress,
      private UserAgent $userAgent,
   ) {}

   public function getEmail()
   {
      return $this->email;
   }

   public function getIpAddress()
   {
      return $this->ipAddress;
   }

   public function getUserAgent()
   {
      return $this->userAgent;
   }
}
