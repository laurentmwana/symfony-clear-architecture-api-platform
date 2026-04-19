<?php

namespace App\IdentityAndAccess\Presentation\Input;

use Symfony\Component\Validator\Constraints as Assert;

class VerifyMagicLinkInput
{
   #[Assert\NotBlank()]
   #[Assert\Length(
      min: 1,
      max: 255,
      minMessage: 'Token cannot be empty',
      maxMessage: 'Token is too long'
   )]
   private ?string $token = null;

   public function getToken()
   {
      return $this->token;
   }

   public function setToken(?string $token)
   {
      $this->token = $token;

      return $this;
   }
}
