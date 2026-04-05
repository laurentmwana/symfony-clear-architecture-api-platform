<?php

namespace App\IdentityAndAccess\Application\Command;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\ValueObject\Password;

final class LoginCommand
{
   public function __construct(private Email $email, private Password $password) {}

   public function getEmail()
   {
      return $this->email;
   }

   public function getPassword()
   {
      return $this->password;
   }
}
