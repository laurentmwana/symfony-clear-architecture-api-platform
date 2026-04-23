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

   public function getIdentifiant(): ?string
   {
      return $this->identifiant;
   }

   public function setIdentifiant(?string $identifiant): static
   {
      $this->identifiant = $identifiant;

      return $this;
   }

   public function getPassword(): ?string
   {
      return $this->password;
   }

   public function setPassword(?string $password): static
   {
      $this->password = $password;

      return $this;
   }
}
