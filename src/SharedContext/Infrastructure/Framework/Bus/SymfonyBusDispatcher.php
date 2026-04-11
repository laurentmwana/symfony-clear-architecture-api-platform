<?php

namespace App\SharedContext\Infrastructure\Framework\Bus;

use App\SharedContext\Application\Bus\BusDispatcher;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyBusDispatcher implements BusDispatcher
{
   use HandleTrait {
      HandleTrait::handle as messengerHandle;
   }

   public function __construct(MessageBusInterface $commandBus)
   {
      $this->messageBus = $commandBus;
   }
   public function dispatch(object $command): mixed
   {
      try {
         return $this->messengerHandle($command);
      } catch (HandlerFailedException $e) {
         while ($e instanceof HandlerFailedException) {
            /** @var \Throwable $e */
            $e = $e->getPrevious();
         }

         throw $e;
      }
   }
}
