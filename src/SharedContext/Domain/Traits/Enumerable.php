<?php

namespace App\SharedContext\Domain\Traits;

trait Enumerable
{
   public static function values()
   {
      return array_map(fn(self $enum) => $enum->value, self::cases());
   }

   public static function names()
   {
      return array_map(fn(self $enum) => $enum->name, self::cases());
   }
}
