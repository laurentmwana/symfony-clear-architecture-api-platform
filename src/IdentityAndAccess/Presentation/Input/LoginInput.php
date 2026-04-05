<?php

namespace App\IdentityAndAccess\Presentation\Input;

use Symfony\Component\Validator\Constraints as Assert;

class LoginInput
{
   #[Assert\NotBlank]
   public ?string $identifiant = null;

   #[Assert\NotBlank()]
   public ?string $password = null;

   /**
    * Get the value of password
    */
   public function getPassword()
   {
      return $this->password;
   }

   /**
    * Set the value of password
    *
    * @return  self
    */
   public function setPassword($password)
   {
      $this->password = $password;

      return $this;
   }

   /**
    * Get the value of identifiant
    */
   public function getIdentifiant()
   {
      return $this->identifiant;
   }

   /**
    * Set the value of identifiant
    *
    * @return  self
    */
   public function setIdentifiant($identifiant)
   {
      $this->identifiant = $identifiant;

      return $this;
   }
}
