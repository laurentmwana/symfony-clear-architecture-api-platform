<?php

namespace App\SharedContext\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\SharedContext\Domain\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UuidType extends Type
{
   public const NAME = 'uuid_type';

   public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
   {
      return $platform->getStringTypeDeclarationSQL([
         'length' => 36,
         'fixed' => true
      ]);
   }

   public function convertToPHPValue($value, AbstractPlatform $platform): ?Uuid
   {
      if ($value === null) {
         return null;
      }

      return new Uuid($value);
   }

   public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
   {
      if ($value === null) {
         return null;
      }

      if ($value instanceof Uuid) {
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
