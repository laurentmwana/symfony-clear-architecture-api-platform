<?php

namespace App\SharedContext\Application\Bus\Message;

interface MessageHandlerBus
{
   public function dispatch(object $message): void;
}
