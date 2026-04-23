<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Service;

use App\IdentityAndAccess\Domain\Entity\OneTimePassword;
use App\IdentityAndAccess\Domain\Service\MagicLinkUrlGenerator;

class SymfonyMagicLinkUrlGenerator implements MagicLinkUrlGenerator
{
   private const PATH = '/auth/magic-link/verify';

   public function __construct(
      private string $frontendUrl
   ) {}

   public function generate(OneTimePassword $magicLink): string
   {
      return sprintf(
         '%s%s?token=%s',
         rtrim($this->frontendUrl, '/'),
         self::PATH,
         $magicLink->getToken()->value()
      );
   }
}
