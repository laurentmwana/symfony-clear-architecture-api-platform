<?php

namespace App\OneTimePassword\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\OneTimePassword\Domain\ValueObject\OtpPassword;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class OtpPasswordType extends Type
{
  public const NAME = 'otp_password_type';

  public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
  {
    return $platform->getStringTypeDeclarationSQL([
      'length' => 255,
    ]);
  }

  public function convertToPHPValue($value, AbstractPlatform $platform): ?OtpPassword
  {
    if ($value === null) {
      return null;
    }

    return new OtpPassword($value);
  }

  public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
  {
    if ($value === null) {
      return null;
    }

    if ($value instanceof OtpPassword) {
      return (string) $value->value();
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
