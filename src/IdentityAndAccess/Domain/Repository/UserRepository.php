<?php

namespace App\IdentityAndAccess\Domain\Repository;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Domain\Entity\User;
use App\SharedContext\Domain\ValueObject\Phone;

interface UserRepository
{
   public function findByEmail(Email $email): ?User;
   public function findByEmailOrPhone(Email|Phone $identifiant): ?User;
}
