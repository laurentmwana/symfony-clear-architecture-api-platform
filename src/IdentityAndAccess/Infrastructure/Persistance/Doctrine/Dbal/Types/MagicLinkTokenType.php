<?php

namespace App\IdentityAndAccess\Infrastructure\Persistance\Doctrine\Dbal\Types;

use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class MagicLinkTokenType extends Type
{
   public const NAME = 'magic_link_token_type';

   public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
   {
      return $platform->getStringTypeDeclarationSQL([
         'length' => 255,
      ]);
   }

   public function convertToPHPValue($value, AbstractPlatform $platform): ?MagicLinkToken
   {
      if ($value === null) {
         return null;
      }

      return new MagicLinkToken($value);
   }

   public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
   {
      if ($value === null) {
         return null;
      }

      if ($value instanceof MagicLinkToken) {
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
