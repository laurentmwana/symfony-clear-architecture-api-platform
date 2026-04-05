<?php

namespace App\SharedContext\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\SharedContext\Domain\ValueObject\Name;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class NameType extends Type
{
   public const NAME = 'name_type';

   public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
   {
      return $platform->getStringTypeDeclarationSQL([
         'length' => 255,
      ]);
   }

   public function convertToPHPValue($value, AbstractPlatform $platform): ?Name
   {
      if ($value === null) {
         return null;
      }

      return new Name($value);
   }

   public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
   {
      if ($value === null) {
         return null;
      }

      if ($value instanceof Name) {
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
