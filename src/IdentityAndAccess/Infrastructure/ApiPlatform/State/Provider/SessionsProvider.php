<?php

namespace App\IdentityAndAccess\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\IdentityAndAccess\Application\Query\GetUserSessionsQuery;
use App\IdentityAndAccess\Domain\Entity\Session;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Presentation\Output\SessionOutput;
use App\SharedContext\Application\Bus\BusDispatcher;
use Symfony\Bundle\SecurityBundle\Security;

final class SessionsProvider implements ProviderInterface
{
   public function __construct(
      private BusDispatcher $bus,
      private Security $security,
   ) {}

   public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
   {
      $user = $this->security->getUser();

      if (!($user instanceof User)) {
         throw new \RuntimeException('Missing authenticated user.');
      }

      $sessions = $this->bus->dispatch(
         new GetUserSessionsQuery($user->getId())
      );

      dd($sessions);

      return array_map(
         fn(Session $session) => $this->toOutput($session),
         $sessions
      );
   }

   private function toOutput(Session $session): SessionOutput
   {
      return new SessionOutput(
         (string) $session->getId(),
         $session->getIpAddress()?->value(),
         $session->getUserAgent()?->value(),
         $session->getCreatedAt()?->format(DATE_ATOM),
      );
   }
}
