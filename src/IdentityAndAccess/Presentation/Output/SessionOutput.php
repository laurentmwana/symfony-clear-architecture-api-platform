<?php

namespace App\IdentityAndAccess\Presentation\Output;

final class SessionOutput
{
   public function __construct(
      public string $id,
      public string $createdAt,
      public ?string $ipAddress = null,
      public ?string $userAgent = null,
   ) {}
}
