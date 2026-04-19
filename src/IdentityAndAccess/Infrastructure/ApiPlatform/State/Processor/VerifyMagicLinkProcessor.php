<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\VerifyMagicLinkCommand;
use App\IdentityAndAccess\Domain\Exception\AuthenticationException;
use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;
use App\IdentityAndAccess\Presentation\Input\VerifyMagicLinkInput;
use App\IdentityAndAccess\Presentation\Output\JwtTokenOutput;
use App\SharedContext\Application\Bus\BusDispatcher;
use App\SharedContext\Domain\Exception\ValueObjectInvalidException;
use App\SharedContext\Domain\Service\RateLimiter;

class VerifyMagicLinkProcessor implements ProcessorInterface
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

      /** @var \Symfony\Component\HttpFoundation\Request */
      $request = $context['request'] ?? null;

      $this->rateLimiter->throttle($request->getClientIp());

      if (!$data instanceof VerifyMagicLinkInput) {
         throw new \InvalidArgumentException('Expected VerifyMagicLinkInput.');
      }

      try {
         $token = new MagicLinkToken($data->getToken());
      } catch (ValueObjectInvalidException $e) {
         throw new AuthenticationException();
      }

      $command = new VerifyMagicLinkCommand($token);

      $token = $this->bus->dispatch($command);

      return new JwtTokenOutput($token);
   }
}
