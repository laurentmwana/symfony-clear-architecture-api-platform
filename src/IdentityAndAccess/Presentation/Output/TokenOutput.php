<?php

namespace App\IdentityAndAccess\Presentation\Output;

final readonly class TokenOutput
{
   public function __construct(public string $token) {}
}
