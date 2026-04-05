<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\IdentityAndAccess\Domain\ValueObject\Roles;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class RolesType extends Type
{
  public const NAME = 'roles_type';

  public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
  {
    return $platform->getStringTypeDeclarationSQL([
      'length' => 255,
    ]);
  }

  public function convertToPHPValue($value, AbstractPlatform $platform): ?Roles
  {
    if ($value === null) {
      return null;
    }

    $values = is_array($value) ? $value : explode(',', $value);

    return Roles::fromArray($values);
  }

  public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
  {
    if ($value === null) {
      return null;
    }

    if ($value instanceof Roles) {
      return join(',', $value->toArray());
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
