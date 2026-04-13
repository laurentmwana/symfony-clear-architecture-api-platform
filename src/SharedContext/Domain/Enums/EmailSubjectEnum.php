<?php

namespace App\SharedContext\Domain\Enums;

enum EmailSubjectEnum: string
{
   case MAGIC_LINK = 'Your magic login link';
   case RESET_PASSWORD = 'Reset your password';
}
