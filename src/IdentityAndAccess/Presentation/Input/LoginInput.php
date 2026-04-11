<?php

namespace App\IdentityAndAccess\Presentation\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class LoginInput
{
   #[Assert\NotBlank()]
   #[Assert\AtLeastOneOf([
      new Assert\Email(),
      new Assert\Regex('/^\+?[0-9]{9,15}$/'),
   ])]
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
