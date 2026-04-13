<?php

namespace App\IdentityAndAccess\Presentation\Input;

use Symfony\Component\Validator\Constraints as Assert;

class VerifyMagicLinkInput
{
   #[Assert\NotBlank()]
   private ?string $token = null;

   public function getToken()
   {
      return $this->token;
   }

   public function setToken($token)
   {
      $this->token = $token;

      return $this;
   }
}
