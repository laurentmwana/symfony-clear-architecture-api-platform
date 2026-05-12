<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\LoginCommand;
use App\IdentityAndAccess\Domain\Exception\UserCredentialsException;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Presentation\Input\LoginInput;
use App\IdentityAndAccess\Presentation\Output\JwtTokenOutput;
use App\SharedContext\Application\Bus\Command\CommandBus;
use App\SharedContext\Domain\Service\RateLimiter;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\Phone;
use App\SharedContext\Domain\ValueObject\UserAgent;
use Symfony\Component\HttpFoundation\Request;

/**
 * @implements ProcessorInterface<LoginInput, JwtTokenOutput>
 */
class LoginProcessor implements ProcessorInterface
{
   public function __construct(
      private CommandBus $commandBus,
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

      $identifierRaw = $data->getIdentifier();
      $passwordRaw = $data->getPassword();

      if (!$identifierRaw || !$passwordRaw) {
         throw new \InvalidArgumentException('Invalid credentials.');
      }

      $identifier = $this->getIdentifier($identifierRaw);
      $password = Password::fromPlainUnhashed($passwordRaw);
      $ipAddress = new IpAddress($ip);
      $userAgent = $request->headers->get('User-Agent');

      $command = new LoginCommand(
         $identifier,
         $password,
         $ipAddress,
         new UserAgent($userAgent)
      );

      $token = $this->commandBus->dispatch($command);

      return new JwtTokenOutput($token);
   }

   private function getIdentifier(string $identifier): Email|Phone
   {
      if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
         return new Email($identifier);
      }

      try {
         return new Phone($identifier);
      } catch (\Throwable $th) {
         throw new UserCredentialsException();
      }
   }
}
