<?php

namespace App\IdentityAndAccess\Domain\Service;

use App\IdentityAndAccess\Domain\Entity\MagicLink;

interface MagicLinkUrlGenerator
{
   public function generate(MagicLink $magicLink): string;
}
