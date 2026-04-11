<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\LoginCommand;
use App\IdentityAndAccess\Domain\Exception\UserCredentialsException;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\JwtTokenGenerator;
use App\IdentityAndAccess\Domain\Service\PasswordHasher;
use App\SharedContext\Application\Bus\Command\CommandHandlerBus;

final class LoginHandler implements CommandHandlerBus
{
   public function __construct(
      private UserRepository $repository,
      private PasswordHasher $hasher,
      private JwtTokenGenerator $jwt
   ) {}

   public function __invoke(LoginCommand $command): string
   {
      $user = $this->repository->findByEmailOrPhone($command->getIdentifiant());
      if (!$user || !$this->isMatch($user->password(), $command->getPassword())) {
         throw new UserCredentialsException();
      }

      return $this->jwt->generate($user);
   }

   private function isMatch(string $hashPassword, string $plainPassword)
   {
      return $this->hasher->verify($hashPassword, $plainPassword);
   }
}
