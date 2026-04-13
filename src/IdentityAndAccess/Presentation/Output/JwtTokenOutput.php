<?php

namespace App\IdentityAndAccess\Presentation\Output;

final readonly class JwtTokenOutput
{
   public function __construct(public string $token) {}
}
