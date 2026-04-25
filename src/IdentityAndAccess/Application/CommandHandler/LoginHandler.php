<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\LoginCommand;
use App\IdentityAndAccess\Application\Events\UserAuthenticatedEvent;
use App\IdentityAndAccess\Domain\Exception\UserCredentialsException;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\JwtTokenGenerator;
use App\IdentityAndAccess\Domain\Service\PasswordHasher;
use App\SharedContext\Application\Bus\Command\CommandHandlerBus;
use App\SharedContext\Application\Bus\Event\EventBus;

final class LoginHandler implements CommandHandlerBus
{
   public function __construct(
      private UserRepository $repository,
      private PasswordHasher $hasher,
      private JwtTokenGenerator $jwt,
      private EventBus $eventBus,
   ) {}

   public function __invoke(LoginCommand $command): string
   {
      $user = $this->repository->findByEmailOrPhone($command->getIdentifiant());

      if (!$user || !$this->isMatch($user->getPassword(), $command->getPassword())) {
         throw new UserCredentialsException();
      }

      $this->eventBus->dispatch(
         new UserAuthenticatedEvent(
            $user->getId(),
            $command->getIpAddress(),
            $command->getUserAgent()
         )
      );

      return $this->jwt->generate($user);
   }

   private function isMatch(string $hashPassword, string $plainPassword): bool
   {
      return $this->hasher->verify($hashPassword, $plainPassword);
   }
}
