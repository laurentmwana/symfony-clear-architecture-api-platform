<?php

namespace App\SharedContext\Application\Bus;

interface BusDispatcher
{
   public function dispatch(object $object): mixed;
}
