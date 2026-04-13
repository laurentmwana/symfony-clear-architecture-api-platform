<?php

namespace App\SharedContext\Infrastructure\Framework\Services;

use App\SharedContext\Domain\Service\Mailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\SharedContext\Domain\ValueObject\EmailMessage;

final class SymfonyMailer implements Mailer
{
   public function __construct(
      private MailerInterface $mailer
   ) {}

   public function send(EmailMessage $message): void
   {
      $email = (new TemplatedEmail())
         ->from('group@locmobile.com')
         ->to($message->to()->value())
         ->subject($message->subject()->value)
         ->htmlTemplate($message->template()->value)
         ->context($message->context());

      $this->mailer->send($email);
   }
}
