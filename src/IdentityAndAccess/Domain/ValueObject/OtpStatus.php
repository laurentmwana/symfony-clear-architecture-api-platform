<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\IdentityAndAccess\Domain\Enums\OtpStatusEnum;
use Stringable;

final class OtpStatus implements Stringable
{
   public function __construct(
      private readonly OtpStatusEnum $value
   ) {}

   public static function fromString(string $value): self
   {
      return new self(OtpStatusEnum::from($value));
   }

   public function value(): OtpStatusEnum
   {
      return $this->value;
   }

   public function __toString(): string
   {
      return $this->value->value;
   }
}
