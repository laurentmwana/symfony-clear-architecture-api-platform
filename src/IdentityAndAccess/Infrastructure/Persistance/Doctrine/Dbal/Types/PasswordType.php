<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\IdentityAndAccess\Domain\ValueObject\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class PasswordType extends Type
{
  public const NAME = 'password_type';

  public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
  {
    return $platform->getStringTypeDeclarationSQL([
      'length' => 255,
    ]);
  }

  public function convertToPHPValue($value, AbstractPlatform $platform): ?Password
  {
    if ($value === null) {
      return null;
    }

    return Password::fromHash($value);
  }

  public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
  {
    if ($value === null) {
      return null;
    }

    if ($value instanceof Password) {
      return (string) $value;
    }

    return $value;
  }

  public function getName(): string
  {
    return self::NAME;
  }

  public function requiresSQLCommentHint(AbstractPlatform $platform): bool
  {
    return true;
  }
}
