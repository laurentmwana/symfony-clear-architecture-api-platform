<?php

namespace App\IdentityAndAccess\Domain\ValueObject;

use App\IdentityAndAccess\Domain\Enums\RoleUserEnum;
use App\SharedContext\Domain\Exception\ValueObjectInvalidException;

final class Roles
{
  private array $values;

  private function __construct(array $roles)
  {
    $this->values = $roles;
  }

  public static function default(): self
  {
    return new self([RoleUserEnum::ROLE_USER->value]);
  }

  public static function fromArray(array $roles): self
  {
    if (empty($roles)) {
      return self::default();
    }

    foreach ($roles as $role) {
      if (!RoleUserEnum::tryFrom($role)) {
        throw new ValueObjectInvalidException('Invalid role: ' . $role);
      }
    }

    $roles[] = RoleUserEnum::ROLE_USER->value;
    $roles = array_values(array_unique($roles));

    return new self($roles);
  }

  public function has(string $role): bool
  {
    return in_array($role, $this->values, true);
  }

  public function add(string $role): self
  {
    if (!RoleUserEnum::tryFrom($role)) {
      throw new ValueObjectInvalidException('Invalid role: ' . $role);
    }

    if (in_array($role, $this->values, true)) {
      return $this;
    }

    return new self([...$this->values, $role]);
  }

  public function remove(string $role): self
  {
    if ($role === RoleUserEnum::ROLE_USER->value) {
      return $this;
    }

    $key = array_search($role, $this->values, true);
    if ($key === false) {
      return $this;
    }

    $new = $this->values;
    unset($new[$key]);

    return new self(array_values($new));
  }

  public function toArray(): array
  {
    return $this->values;
  }

  public function equals(self $other): bool
  {
    return $this->values == $other->values;
  }

  public function isSimpleUser(): bool
  {
    return $this->values == [RoleUserEnum::ROLE_USER->value];
  }
}
