<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class MagicLinkToken implements Stringable
{
   public function __construct(
      private readonly string $value
   ) {
      if (strlen($value) !== 64) {
         throw new ValueObjectInvalidException(
            'MagicLinkToken must be a 64-character alphanumeric string'
         );
      }
   }

   public function value(): string
   {
      return $this->value;
   }

   public function __toString(): string
   {
      return $this->value;
   }
}
