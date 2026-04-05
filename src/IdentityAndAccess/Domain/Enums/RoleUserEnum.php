<?php

namespace App\IdentityAndAccess\Domain\Enums;

use App\SharedContext\Domain\Traits\Enumerable;

enum RoleUserEnum: string
{
   use Enumerable;

   case ROLE_USER = "ROLE_USER";
   case ROLE_ADMIN = "ROLE_ADMIN";

   public static function forDefault()
   {
      return [self::ROLE_USER->value];
   }

   public static function forAdmin()
   {
      return [self::ROLE_USER->value, self::ROLE_ADMIN->value];
   }
}
