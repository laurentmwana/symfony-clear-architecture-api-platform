<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\IdentityAndAccess\Application\Command\SendMagicLinkCommand;
use App\IdentityAndAccess\Presentation\Input\MagicLinkInput;
use App\IdentityAndAccess\Presentation\Output\MagicLinkOutput;
use App\SharedContext\Application\Bus\BusDispatcher;
use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\IpAddress;
use App\SharedContext\Domain\ValueObject\UserAgent;
use Symfony\Component\HttpFoundation\RequestStack;

class MagicLinkProcessor implements ProcessorInterface
{
   public function __construct(
      private BusDispatcher $bus,
      private RequestStack $request,
   ) {}

   public function process(
      mixed $data,
      Operation $operation,
      array $uriVariables = [],
      array $context = []
   ): MagicLinkOutput {
      if (!$data instanceof MagicLinkInput) {
         throw new \InvalidArgumentException('Expected MagicLinkInput.');
      }

      $request = $this->request->getCurrentRequest();

      $command = new SendMagicLinkCommand(
         new Email($data->getEmail()),
         new IpAddress($request->getClientIp()),
         new UserAgent($request->headers->get('User-Agent', null))
      );

      $this->bus->dispatch($command);

      return new MagicLinkOutput(
         status: 'success',
         message: 'If an account exists, a magic link has been sent.',
      );
   }
}
