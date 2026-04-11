<?php

namespace App\IdentityAndAccess\Application\Command;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\SharedContext\Domain\ValueObject\Phone;

final class LoginCommand
{
   public function __construct(private Email|Phone $identifiant, private Password $password) {}

   public function getIdentifiant(): Email|Phone
   {
      return $this->identifiant;
   }

   public function getPassword(): Password
   {
      return $this->password;
   }
}
