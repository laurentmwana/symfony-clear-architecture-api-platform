<?php

namespace App\IdentityAndAccess\Domain\Enums;

use App\SharedContext\Domain\Traits\Enumerable;

enum OneTimePasswordTargetEnum: string
{
   use Enumerable;

   case PHONE = "phone";
   case EMAIL = "email";
}
