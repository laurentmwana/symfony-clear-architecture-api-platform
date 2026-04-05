<?php

namespace App\IdentityAndAccess\Domain\Service;

interface PasswordHasher
{
  public function hash(string $plainPassword): string;

  public function verify(string $hashedPassword, string $plainPassword): bool;

  public function needsRehash(string $hashedPassword): bool;
}
