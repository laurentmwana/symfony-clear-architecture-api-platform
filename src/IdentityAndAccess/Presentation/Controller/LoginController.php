<?php

namespace App\IdentityAndAccess\Presentation\Controller;

use App\SharedContext\Domain\ValueObject\Email;
use App\IdentityAndAccess\Application\Command\LoginCommand;
use App\IdentityAndAccess\Application\Handler\LoginHandler;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Presentation\Input\LoginInput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

#[AsController]
class LoginController
{
   public function __construct(private LoginHandler $usecase) {}

   public function __invoke(
      #[MapRequestPayload] LoginInput $input
   ): JsonResponse {

      $command = new LoginCommand(
         new Email($input->getEmail()),
         Password::fromHash($input->getPassword())
      );

      $token = $this->usecase->handle($command);

      if (null === $token) {
         return new JsonResponse(
            ['message' => 'Invalid credentials'],
            401
         );
      }

      return new JsonResponse(['token' => $token]);
   }
}
