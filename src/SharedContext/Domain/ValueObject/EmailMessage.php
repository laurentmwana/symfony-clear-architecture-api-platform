<?php

namespace App\SharedContext\Domain\ValueObject;

use App\SharedContext\Domain\Enums\EmailSubjectEnum;
use App\SharedContext\Domain\Enums\EmailTemplateEnum;

final class EmailMessage
{
   /**
    * @param Email $to
    * @param EmailTemplateEnum $template
    * @param EmailSubjectEnum $subject
    * @param array<string, mixed> $context
    */
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

   /**
    * @return array<string, mixed>
    */
   public function context(): array
   {
      return $this->context;
   }
}
