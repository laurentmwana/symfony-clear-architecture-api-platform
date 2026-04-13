<?php

namespace App\IdentityAndAccess\Domain\Repository;

use App\IdentityAndAccess\Domain\Entity\MagicLink;
use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;
use App\SharedContext\Domain\Repository\RepositoryInterface;
use App\SharedContext\Domain\ValueObject\Email;

interface MagicLinkRepository extends RepositoryInterface
{
   public function findValidByEmail(Email $email): ?MagicLink;
   public function findByToken(MagicLinkToken $token): ?MagicLink;
}
