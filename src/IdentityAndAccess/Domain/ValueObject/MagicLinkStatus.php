<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\IdentityAndAccess\Domain\Enums\MagicLinkStatusEnum;
use Stringable;

final class MagicLinkStatus implements Stringable
{
   public function __construct(
      private readonly MagicLinkStatusEnum $value
   ) {}

   public static function fromString(string $value): self
   {
      return new self(MagicLinkStatusEnum::from($value));
   }

   public function value(): MagicLinkStatusEnum
   {
      return $this->value;
   }

   public function __toString(): string
   {
      return $this->value;
   }
}
