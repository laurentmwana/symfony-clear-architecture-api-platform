<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Enums\EmailSubjectEnum;
use App\SharedContext\Domain\Enums\EmailTemplateEnum;
use App\SharedContext\Domain\ValueObject\Email;

final class EmailMessage
{
   public function __construct(
      private Email $to,
      private EmailTemplateEnum $template,
      private EmailSubjectEnum $subject,
      private array $context = [],
   ) {}

   public function to(): Email
   {
      return $this->to;
   }

   public function subject(): EmailSubjectEnum
   {
      return $this->subject;
   }

   public function template(): EmailTemplateEnum
   {
      return $this->template;
   }

   public function context(): array
   {
      return $this->context;
   }
}
