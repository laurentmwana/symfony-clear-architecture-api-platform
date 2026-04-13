<?php

namespace App\SharedContext\Domain\Enums;

enum EmailTemplateEnum: string
{
   case MAGIC_LINK = 'emails/auth/magic_link.html.twig';
   case RESET_PASSWORD = 'emails/auth/reset_password.html.twig';
}
