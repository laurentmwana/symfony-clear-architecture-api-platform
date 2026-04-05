<?php

namespace App\IdentityAndAccess\Application\Handler;

use App\IdentityAndAccess\Application\Command\LoginCommand;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\JwtTokenGenerator;
use App\IdentityAndAccess\Domain\Service\PasswordHasher;

class LoginHandler
{
  public function __construct(
    private UserRepository $repository,
    private PasswordHasher $hasher,
    private JwtTokenGenerator $jwt
  ) {}

  public function handle(LoginCommand $command)
  {
    $user = $this->repository->findByEmail($command->getEmail());
    if (!$user || !$this->isMatch($command->getPassword(), $user->password())) {
      return null;
    }

    return $this->jwt->generate($user);
  }

  private function isMatch(string $plainPassword, string $hashPassword)
  {
    return $this->hasher->verify($hashPassword, $plainPassword);
  }
}
