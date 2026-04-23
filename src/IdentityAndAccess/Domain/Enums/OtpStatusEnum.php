<?php

namespace App\IdentityAndAccess\Domain\Enums;

use App\SharedContext\Domain\Traits\Enumerable;

enum OtpStatusEnum: string
{
   use Enumerable;

   case USED = "used";
   case PENDING = "pending";
}
