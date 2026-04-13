<?php

namespace App\SharedContext\Domain\Service;

use App\SharedContext\Domain\ValueObject\EmailMessage;

interface Mailer
{
   public function send(EmailMessage $message): void;
}
