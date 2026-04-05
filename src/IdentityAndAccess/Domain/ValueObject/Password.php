<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use App\IdentityAndAccess\Domain\Service\PasswordHasher;
use Stringable;

final class Password implements Stringable
{
  private string $value;

  private function __construct(string $hashedPassword)
  {
    $this->value = $hashedPassword;
  }

  public static function fromPlain(
    string $plainPassword,
    PasswordHasher $hasher
  ): self {
    $plainPassword = trim($plainPassword);

    if ($plainPassword === '') {
      throw new ValueObjectInvalidException('Password cannot be empty.');
    }

    if (mb_strlen($plainPassword) < 12) {
      throw new ValueObjectInvalidException(
        'Password must be at least 12 characters.'
      );
    }

    return new self($hasher->hash($plainPassword));
  }

  public static function fromHash(string $hash): self
  {
    return new self($hash);
  }

  public function verify(
    string $plainPassword,
    PasswordHasher $hasher
  ): bool {
    return $hasher->verify($this->value, $plainPassword);
  }

  public function needsRehash(PasswordHasher $hasher): bool
  {
    return $hasher->needsRehash($this->value);
  }

  public function __toString(): string
  {
    return $this->value;
  }

  public function equals(self $other): bool
  {
    return hash_equals($this->value, $other->value);
  }
}
