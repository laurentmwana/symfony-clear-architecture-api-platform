<?php

namespace App\IdentityAndAccess\Application\QueryHandler;

use App\IdentityAndAccess\Application\Query\GetUserSessionsQuery;
use App\IdentityAndAccess\Domain\Repository\SessionRepository;
use App\SharedContext\Application\Bus\Query\QueryHandlerBus;

class GetUserSessionsHandler implements QueryHandlerBus
{
   public function __construct(
      private SessionRepository $session
   ) {}

   public function __invoke(GetUserSessionsQuery $query)
   {
      $sessions = $this->session->findAllByUserId($query->getUserId());

      return $sessions;
   }
}
