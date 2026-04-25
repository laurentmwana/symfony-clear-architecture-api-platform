<?php

namespace App\IdentityAndAccess\Domain\Repository;

use App\IdentityAndAccess\Domain\Entity\Session;
use App\SharedContext\Domain\Repository\RepositoryInterface;
use App\SharedContext\Domain\ValueObject\Uuid;

interface SessionRepository extends RepositoryInterface
{
   public function findByUserId(Uuid $userId): ?Session;
}
