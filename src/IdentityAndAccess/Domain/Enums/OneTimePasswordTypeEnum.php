<?php

namespace App\IdentityAndAccess\Domain\Enums;

use App\SharedContext\Domain\Traits\Enumerable;

enum OneTimePasswordTypeEnum: string
{
   use Enumerable;

   case MAGIC_OTP_LOGIN = "MAGIC_OTP_LOGIN";
   case VERIFY_PHONE = "VERIFY_PHONE";
   case VERIFY_EMAIL = "VERIFY_EMAIL";
   case RESET_PASSWORD = "RESET_PASSWORD";
}
