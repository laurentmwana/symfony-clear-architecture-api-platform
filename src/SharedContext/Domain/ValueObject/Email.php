<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use Stringable;

final class Email implements Stringable
{
   private string $value;

   public function __construct(string $value)
   {
      $value = trim($value);

      if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
         throw new ValueObjectInvalidException(sprintf('Invalid email: "%s".', $value));
      }

      $this->value = $value;
   }

   public function value()
   {
      return $this->value;
   }

   public function __toString(): string
   {
      return $this->value;
   }

   public function equals(self $other): bool
   {
      return $this->value === $other->value;
   }
}
