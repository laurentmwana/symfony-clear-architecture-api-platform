<?php

namespace App\IdentityAndAccess\Infrastructure\Framework\Security;

use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Uuid;
use App\IdentityAndAccess\Domain\Entity\User;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Domain\ValueObject\Roles;
use App\SharedContext\Domain\ValueObject\Name;
use App\SharedContext\Domain\ValueObject\Phone;
use Symfony\Component\Security\Core\User\{
   UserInterface,
   PasswordAuthenticatedUserInterface
};

final readonly class SecurityUser implements
   UserInterface,
   PasswordAuthenticatedUserInterface
{
   private function __construct(
      private Uuid $id,
      private Name $name,
      private Email $email,
      private Phone $phone,
      private Password $password,
      private Roles $roles
   ) {}

   public static function create(User $user): self
   {
      return new self(
         $user->getId(),
         $user->getName(),
         $user->getEmail(),
         $user->getPhone(),
         $user->getPassword(),
         $user->getRoles()
      );
   }

   public function toDomainUser(): User
   {
      return User::create(
         $this->id,
         $this->name,
         $this->email,
         $this->phone,
         $this->password,
         $this->roles,
      );
   }

   #[\Override]
   public function getPassword(): ?string
   {
      return $this->password;
   }

   #[\Override]
   public function getRoles(): array
   {
      return $this->roles->toArray();
   }

   #[\Override]
   public function getUserIdentifier(): string
   {
      return $this->email;
   }

   public function getId()
   {
      return $this->id->value();
   }
}
