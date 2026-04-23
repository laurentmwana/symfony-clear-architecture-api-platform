<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;

class OtpCode implements Stringable
{
   private const MAX_LENGTH = 6;

   private string $value;

   public function __construct(string $value)
   {
      if (is_string($value) && strlen($value) != self::MAX_LENGTH) {
         throw new InvalidArgumentException("");
      }

      $this->value = $value;
   }



   public function __toString(): string
   {
      return $this->value;
   }
}
