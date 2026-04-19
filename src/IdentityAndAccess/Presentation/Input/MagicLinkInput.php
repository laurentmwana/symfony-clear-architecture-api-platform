<?php

namespace App\IdentityAndAccess\Presentation\Input;

use Symfony\Component\Validator\Constraints as Assert;

class MagicLinkInput
{
   #[Assert\NotBlank()]
   #[Assert\Email()]
   private ?string $email = null;

   public function getEmail(): ?string
   {
      return $this->email;
   }

   public function setEmail(?string $email): static
   {
      $this->email = $email;

      return $this;
   }
}
