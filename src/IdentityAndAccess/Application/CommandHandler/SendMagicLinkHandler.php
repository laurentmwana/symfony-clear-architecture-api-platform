<?php

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\SendMagicLinkCommand;
use App\IdentityAndAccess\Domain\Entity\MagicLink;
use App\IdentityAndAccess\Domain\Exception\EmailNotFoundException;
use App\IdentityAndAccess\Domain\Exception\UserCredentialsException;
use App\IdentityAndAccess\Domain\Repository\MagicLinkRepository;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\MagicLinkUrlGenerator;
use App\IdentityAndAccess\Domain\ValueObject\MagicLinkToken;
use App\SharedContext\Application\Bus\Command\CommandHandlerBus;
use App\SharedContext\Domain\Enums\EmailSubjectEnum;
use App\SharedContext\Domain\Enums\EmailTemplateEnum;
use App\SharedContext\Domain\Service\Mailer;
use App\SharedContext\Domain\Service\TokenGenerator;
use App\SharedContext\Domain\Service\UuidGenerator;
use App\SharedContext\Domain\ValueObject\EmailMessage;

class SendMagicLinkHandler implements CommandHandlerBus
{
   public function __construct(
      private UserRepository $user,
      private MagicLinkRepository $magicLink,
      private UuidGenerator $uuid,
      private TokenGenerator $token,
      private Mailer $mailer,
      private MagicLinkUrlGenerator $magicLinkUrl
   ) {}



   public function __invoke(SendMagicLinkCommand $command): MagicLink
   {
      $email = $command->getEmail();

      $user = $this->user->findByEmail($email);

      if (!$user) {
         throw new UserCredentialsException(
            sprintf('No account found for email "%s".', $email->value())
         );
      }

      $magicLink = $this->magicLink->findValidByEmail($email);

      if (!$magicLink) {
         $magicLink = MagicLink::create(
            $this->uuid->generate(),
            $email,
            new MagicLinkToken(
               $this->token->alphaNumeric(64)
            ),
            $command->getIpAddress(),
            $command->getUserAgent()
         );

         $this->magicLink->save($magicLink);
      }

      $this->mailer->send(
         new EmailMessage(
            to: $email,
            template: EmailTemplateEnum::MAGIC_LINK,
            subject: EmailSubjectEnum::MAGIC_LINK,
            context: [
               'token' => $magicLink->getToken()->value(),
               'expiresAt' => $magicLink->getExpiresAt(),
               'magicLinkUrl' => $this->magicLinkUrl->generate($magicLink)
            ],
         )
      );

      return $magicLink;
   }
}
