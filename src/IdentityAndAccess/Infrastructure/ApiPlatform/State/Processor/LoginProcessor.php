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
use Symfony\Component\HttpFoundation\Request;

/**
 * @implements ProcessorInterface<LoginInput, JwtTokenOutput>
 */
class LoginProcessor implements ProcessorInterface
{
   public function __construct(
      private BusDispatcher $bus,
      private RateLimiter $rateLimiter
   ) {}

   public function process(
      mixed $data,
      Operation $operation,
      array $uriVariables = [],
      array $context = []
   ): JwtTokenOutput {
      /** @var Request|null $request */
      $request = $context['request'] ?? null;

      if (!$request) {
         throw new \RuntimeException('Missing request in context.');
      }

      $ip = $request->getClientIp();

      if (!$ip) {
         throw new \RuntimeException('Cannot resolve client IP.');
      }

      $this->rateLimiter->throttle($ip);

      $identifiantRaw = $data->getIdentifiant();
      $passwordRaw = $data->getPassword();

      if (!$identifiantRaw || !$passwordRaw) {
         throw new \InvalidArgumentException('Invalid credentials.');
      }

      $identifiant = $this->getIdentifiant($identifiantRaw);
      $password = Password::fromPlainUnhashed($passwordRaw);

      $token = $this->bus->dispatch(
         new LoginCommand($identifiant, $password)
      );

      return new JwtTokenOutput($token);
   }

   private function getIdentifiant(string $identifiant): Email|Phone
   {
      if (filter_var($identifiant, FILTER_VALIDATE_EMAIL)) {
         return new Email($identifiant);
      }

      try {
         return new Phone($identifiant);
      } catch (\Throwable $th) {
         throw new UserCredentialsException();
      }
   }
}
