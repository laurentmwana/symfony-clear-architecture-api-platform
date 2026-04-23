<?php

namespace App\IdentityAndAccess\Presentation\Contraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PhoneOrEmailValidator extends ConstraintValidator
{
   /**
    * @param string|null $value
    * @param PhoneOrEmail $constraint
    */
   public function validate($value, Constraint $constraint): void
   {
      if (null === $value || '' === $value) {
         return;
      }

      if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
         return;
      }

      $cleanedPhone = (string) preg_replace('/[\s\-]/', '', $value);

      if (preg_match('/^\+\d{8,15}$/', $cleanedPhone)) {
         return;
      }

      $this->context->buildViolation($constraint->message)
         ->setParameter('{{ value }}', $this->formatValue($value))
         ->addViolation();
   }
}
