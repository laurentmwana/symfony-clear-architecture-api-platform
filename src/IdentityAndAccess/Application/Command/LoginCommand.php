<?php

namespace App\IdentityAndAccess\Application\Command;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\SharedContext\Domain\ValueObject\Phone;

final class LoginCommand
{
   public function __construct(private Email|Phone $identifiant, private Password $password) {}

   public function getPassword()
   {
      return $this->password;
   }


   public function getIdentifiant()
   {
      return $this->identifiant;
   }
}
