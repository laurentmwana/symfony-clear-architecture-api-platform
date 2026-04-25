<?php

namespace App\IdentityAndAccess\Application\Command;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\Phone;
use App\SharedContext\Domain\ValueObject\UserAgent;

final class LoginCommand
{
   public function __construct(
      private Email|Phone $identifiant,
      private Password $password,
      private ?IpAddress $ipAddress = null,
      private ?UserAgent $userAgent = null
   ) {}

   public function getIdentifiant(): Email|Phone
   {
      return $this->identifiant;
   }

   public function getPassword(): Password
   {
      return $this->password;
   }

   public function getIpAddress(): ?IpAddress
   {
      return $this->ipAddress;
   }

   public function getUserAgent(): ?UserAgent
   {
      return $this->userAgent;
   }
}
