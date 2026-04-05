<?php

namespace App\SharedContext\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\SharedContext\Domain\ValueObject\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EmailType extends Type
{
   public const NAME = 'email_type';

   public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
   {
      return $platform->getStringTypeDeclarationSQL([
         'length' => 255,
      ]);
   }

   public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
   {
      if ($value === null) {
         return null;
      }

      return new Email($value);
   }

   public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
   {
      if ($value === null) {
         return null;
      }

      if ($value instanceof Email) {
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
