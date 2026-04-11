<?php

namespace App\OneTimePassword\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;

final class OtpPassword
{
  private string $value;

  public function __construct(string $value)
  {
    if (!preg_match('/^\d{6}$/', $value)) {
      throw new ValueObjectInvalidException('OTP must be a 6-digit number.');
    }

    $this->value = $value;
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
