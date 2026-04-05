<?php

namespace App\IdentityAndAccess\Presentation\Input;

use Symfony\Component\Validator\Constraints as Assert;

class LoginInput
{
   #[Assert\NotBlank]
   #[Assert\Email]
   public ?string $email = null;

   #[Assert\NotBlank()]
   public ?string $password = null;

   /**
    * Get the value of email
    */
   public function getEmail()
   {
      return $this->email;
   }

   /**
    * Set the value of email
    *
    * @return  self
    */
   public function setEmail($email)
   {
      $this->email = $email;

      return $this;
   }

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
}
