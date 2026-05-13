<?php

declare(strict_types=1);

namespace App\IdentityAndAccess\Application\CommandHandler;

use App\IdentityAndAccess\Application\Command\MagicLoginCommand;
use App\IdentityAndAccess\Domain\Enums\OtpTypeEnum;
use App\IdentityAndAccess\Domain\Repository\UserRepository;
use App\IdentityAndAccess\Domain\Service\OtpGenerator;
use App\IdentityAndAccess\Domain\ValueObject\DeliveryMethod;
use App\IdentityAndAccess\Domain\ValueObject\OtpType;
use App\SharedContext\Application\Bus\Command\CommandHandler;
use App\SharedContext\Application\Bus\Message\MessageBus;
use App\SharedContext\Application\Command\SendMessageCommand;
use App\SharedContext\Domain\Enums\MessageSubjectEnum;
use App\SharedContext\Domain\Enums\MessageTemplateEnum;
use App\SharedContext\Domain\ValueObject\Message;

class SendMagicLoginHandler implements CommandHandler
{
   public function __construct(
      private UserRepository $userRepository,
      private OtpGenerator $otpGenerator,
      private MessageBus $messageBus,
   ) {}

   public function __invoke(MagicLoginCommand $command): OtpTypeEnum
   {
      $identifier = $command->getIdentifier();
      $user = $this->userRepository->findByIdentifier($identifier);

      $type = OtpType::magicLogin();
      $typeEnum = $type->toEnum();

      if (!$user) {
         return $typeEnum;
      }

      $channel = $identifier->getDeliveryMethod();
      $method = DeliveryMethod::fromEnum($channel);

      $otp = $this->otpGenerator->generate($user, $type, $method);

      $context = [
         'username' => $user->getName(),
         'code' => $otp->getCode()->value(),
         'attempts' => $otp->getAttempts()->value(),
         'expiresAt' => $otp->getExpiresAt(),
      ];

      $messageCommand = new SendMessageCommand(
         new Message(
            recipient: $identifier,
            template: MessageTemplateEnum::MAGIC_LOGIN_EMAIL,
            subject: MessageSubjectEnum::MAGIC_LOGIN,
            context: $context,
         ),
         $channel,
      );

      $this->messageBus->dispatch($messageCommand);

      return $typeEnum;
   }
}
