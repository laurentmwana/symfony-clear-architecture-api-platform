<?php

namespace App\IdentityAndAccess\Application\Message;

use App\SharedContext\Application\Bus\Message\AsyncMessage;

class SendMagicLinkEmailMessage implements AsyncMessage
{
   public function __invoke()
   {
      throw new \Exception('Not implemented');
   }
}
