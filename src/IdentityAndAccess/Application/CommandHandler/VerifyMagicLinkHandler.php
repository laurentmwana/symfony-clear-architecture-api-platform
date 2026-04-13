<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\VerifyMagicLinkCommand;
use App\IdentityAndAccess\Domain\Exception\InvalidMagicLinkException;
use App\IdentityAndAccess\Domain\Repository\MagicLinkRepository;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\JwtTokenGenerator;
use App\SharedContext\Application\Bus\Command\CommandHandlerBus;

class VerifyMagicLinkHandler implements CommandHandlerBus
{
   public function __construct(
      private MagicLinkRepository $magicLink,
      private UserRepository $user,
      private JwtTokenGenerator $jwt
   ) {}

   public function __invoke(VerifyMagicLinkCommand $command): string
   {
      $magicLink = $this->magicLink->findByToken($command->getToken());

      if (!$magicLink) {
         throw new InvalidMagicLinkException();
      }

      if ($magicLink->isExpired()  || $magicLink->isUsed()) {
         throw new InvalidMagicLinkException();
      }

      $user = $this->user->findByEmail($magicLink->getEmail());

      if (!$user) {
         throw new InvalidMagicLinkException();
      }

      $magicLink->markAsUsed();

      $this->magicLink->save($magicLink);

      return $this->jwt->generate($user);
   }
}
