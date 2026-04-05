<?php

namespace App\IdentityAndAccess\Domain\Service;

use App\IdentityAndAccess\Domain\Entity\User;

interface JwtTokenGenerator
{
  public function generate(User $user): string;
}
