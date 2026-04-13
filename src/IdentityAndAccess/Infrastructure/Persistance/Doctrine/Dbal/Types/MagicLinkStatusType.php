<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\IdentityAndAccess\Domain\ValueObject\MagicLinkStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class MagicLinkStatusType extends Type
{
   public const NAME = 'magic_link_status_type';

   public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
   {
      return $platform->getStringTypeDeclarationSQL([
         'length' => 255,
      ]);
   }

   public function convertToPHPValue($value, AbstractPlatform $platform): ?MagicLinkStatus
   {
      if ($value === null) {
         return null;
      }

      return MagicLinkStatus::fromString($value);
   }

   public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
   {
      if ($value === null) {
         return null;
      }

      if ($value instanceof MagicLinkStatus) {
         return $value->value()->value;
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
