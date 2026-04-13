<?php

namespace App\IdentityAndAccess\Presentation\Output;

class MagicLinkOutput
{
   public function __construct(
      public string $status,
      public string $message
   ) {}
}
