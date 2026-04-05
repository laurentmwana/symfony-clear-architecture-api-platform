<?php

namespace App\IdentityAndAccess\Domain\Repository;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\Entity\User;

interface UserRepository
{
   public function findByEmail(Email $email): ?User;
}
