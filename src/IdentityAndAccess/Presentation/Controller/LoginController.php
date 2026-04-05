<?php

namespace App\IdentityAndAccess\Presentation\Controller;

use App\IdentityAndAccess\Application\Command\LoginCommand;
use App\IdentityAndAccess\Application\Handler\LoginHandler;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Presentation\Input\LoginInput;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Phone;
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

      $identifiant = $input->getIdentifiant();

      try {
         $emailOrPhone = new Email($identifiant);
      } catch (\Exception $e) {
         try {
            $emailOrPhone = new Phone($identifiant);
         } catch (\Exception $e) {
            return new JsonResponse(
               ['message' => 'Invalid email or phone format'],
               422
            );
         }
      }

      $plainPassword = Password::fromHash($input->getPassword());

      $command = new LoginCommand($emailOrPhone, $plainPassword);

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
