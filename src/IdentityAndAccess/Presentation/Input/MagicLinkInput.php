<?php

namespace App\IdentityAndAccess\Presentation\Input;

use Symfony\Component\Validator\Constraints as Assert;

class MagicLinkInput
{
   #[Assert\NotBlank()]
   #[Assert\Email()]
   private ?string $email = null;

   public function getEmail()
   {
      return $this->email;
   }

   public function setEmail($email)
   {
      $this->email = $email;

      return $this;
   }
}
