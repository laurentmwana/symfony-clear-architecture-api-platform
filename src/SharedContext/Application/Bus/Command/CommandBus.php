<?php

namespace App\SharedContext\Application\Bus\Command;

interface CommandBus
{
   public function handle(object $object): mixed;
}
