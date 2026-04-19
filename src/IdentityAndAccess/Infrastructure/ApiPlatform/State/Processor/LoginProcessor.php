<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\LoginCommand;
use App\IdentityAndAccess\Domain\Exception\UserCredentialsException;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Presentation\Input\LoginInput;
use App\IdentityAndAccess\Presentation\Output\JwtTokenOutput;
use App\SharedContext\Application\Bus\BusDispatcher;
use App\SharedContext\Domain\Service\RateLimiter;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Phone;

class LoginProcessor implements ProcessorInterface
{
   public function __construct(
      private BusDispatcher $bus,
      private RateLimiter $rateLimiter
   ) {}

   public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
   {
      /** @var \Symfony\Component\HttpFoundation\Request */
      $request = $context['request'] ?? null;

      $this->rateLimiter->throttle($request->getClientIp());

      if (!$data instanceof LoginInput) {
         throw new \InvalidArgumentException('Expected instance of LoginInput.');
      }

      $identifiant = $this->getIdentifiant($data->getIdentifiant());
      $password = Password::fromPlainUnhashed($data->getPassword());

      $command = new LoginCommand($identifiant, $password);

      $token = $this->bus->dispatch($command);

      return new JwtTokenOutput($token);
   }

   private function getIdentifiant(string $identifiant): Email|Phone
   {
      try {
         return new Phone($identifiant);
      } catch (\Throwable $th) {
         try {
            return new Email($identifiant);
         } catch (\Throwable $th) {
            throw new UserCredentialsException();
         }
      }
   }
}
