<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\VerifyMagicLinkCommand;
use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;
use App\IdentityAndAccess\Presentation\Input\VerifyMagicLinkInput;
use App\IdentityAndAccess\Presentation\Output\JwtTokenOutput;
use App\SharedContext\Application\Bus\BusDispatcher;

class VerifyMagicLinkProcessor implements ProcessorInterface
{
   public function __construct(
      private BusDispatcher $bus,
   ) {}

   public function process(
      mixed $data,
      Operation $operation,
      array $uriVariables = [],
      array $context = []
   ): JwtTokenOutput {

      if (!$data instanceof VerifyMagicLinkInput) {
         throw new \InvalidArgumentException('Expected VerifyMagicLinkInput.');
      }

      $command = new VerifyMagicLinkCommand(
         new MagicLinkToken($data->getToken())
      );

      $token = $this->bus->dispatch($command);

      return new JwtTokenOutput($token);
   }
}
