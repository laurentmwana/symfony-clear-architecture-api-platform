<?php

namespace App\IdentityAndAccess\Presentation\Input;

use App\IdentityAndAccess\Presentation\Contraints\PhoneOrEmail;
use Symfony\Component\Validator\Constraints as Assert;

final class LoginInput
{
   #[Assert\NotBlank()]
   #[PhoneOrEmail()]
   private ?string $identifiant = null;

   #[Assert\NotBlank()]
   private ?string $password = null;

   public function getIdentifiant()
   {
      return $this->identifiant;
   }

   public function setIdentifiant($identifiant)
   {
      $this->identifiant = $identifiant;

      return $this;
   }

   public function getPassword()
   {
      return $this->password;
   }

   public function setPassword($password)
   {
      $this->password = $password;

      return $this;
   }
}
