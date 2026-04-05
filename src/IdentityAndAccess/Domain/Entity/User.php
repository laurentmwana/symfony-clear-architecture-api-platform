<?php

namespace App\IdentityAndAccess\Domain\Entity;

use App\SharedContext\Domain\ValueObject\Email;
use App\SharedContext\Domain\ValueObject\Name;
use App\SharedContext\Domain\ValueObject\Uuid;
use App\IdentityAndAccess\Domain\ValueObject\Password;
use App\IdentityAndAccess\Domain\ValueObject\Roles;
use App\SharedContext\Domain\ValueObject\Phone;
use DateTimeImmutable;

final class User
{
   private readonly Uuid $id;
   private Name $name;
   private Email $email;
   private Phone $phone;
   private Password $password;
   private readonly Roles $roles;
   private readonly DateTimeImmutable $createdAt;
   private ?DateTimeImmutable $updatedAt = null;
   private ?DateTimeImmutable $emailVerifiedAt = null;

   public function __construct(
      Uuid $id,
      Name $name,
      Email $email,
      Phone $phone,
      Password $password,
      ?Roles $roles = null,
      ?DateTimeImmutable $createdAt = null,
      ?DateTimeImmutable $updatedAt = null
   ) {
      $this->id = $id;
      $this->name = $name;
      $this->email = $email;
      $this->password = $password;
      $this->phone = $phone;
      $this->roles = $roles ?? Roles::default();
      $this->createdAt = $createdAt ?? new DateTimeImmutable();
      $this->updatedAt = $updatedAt ?? new DateTimeImmutable();
   }

   public static function create(
      Uuid $id,
      Name $name,
      Email $email,
      Phone $phone,
      Password $password,
      ?Roles $roles = null
   ): self {
      return new self(
         $id,
         $name,
         $email,
         $phone,
         $password,
         $roles ?? Roles::default()
      );
   }

   public function changePassword(Password $newPassword)
   {
      $this->password = $newPassword;
      $this->updatedAt = new \DateTimeImmutable();
   }

   public function isVerified(): bool
   {
      return $this->emailVerifiedAt !== null;
   }

   public function markAsVerified(): void
   {
      $this->emailVerifiedAt = new \DateTimeImmutable();
      $this->updatedAt = new \DateTimeImmutable();
   }

   public function markAsInVerified(): void
   {
      $this->emailVerifiedAt = null;
      $this->updatedAt = new \DateTimeImmutable();
   }

   public function id(): Uuid
   {
      return $this->id;
   }

   public function name(): Name
   {
      return $this->name;
   }

   public function email(): Email
   {
      return $this->email;
   }

   public function phone(): Phone
   {
      return $this->phone;
   }

   public function password(): Password
   {
      return $this->password;
   }

   public function roles(): Roles
   {
      return $this->roles;
   }

   public function createdAt(): DateTimeImmutable
   {
      return $this->createdAt;
   }

   public function updatedAt(): ?DateTimeImmutable
   {
      return $this->updatedAt;
   }
}
