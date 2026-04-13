<?php

namespace App\SharedContext\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\SharedContext\Domain\ValueObject\IpAddress;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class IpAddressType extends Type
{
   public const NAME = 'ip_address_type';

   public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
   {
      return $platform->getStringTypeDeclarationSQL([
         'length' => 255,
      ]);
   }

   public function convertToPHPValue($value, AbstractPlatform $platform): ?IpAddress
   {
      if ($value === null) {
         return null;
      }

      return new IpAddress($value);
   }

   public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
   {
      if ($value === null) {
         return null;
      }

      if ($value instanceof IpAddress) {
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
