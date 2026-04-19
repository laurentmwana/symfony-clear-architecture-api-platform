<?php

namespace App\IdentityAndAccess\Presentation\Contraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneOrEmailValidator extends ConstraintValidator
{
   public function validate($value, Constraint $constraint): void
   {
      /* @var \App\IdentityAndAccess\Presentation\Constraints\PhoneOrEmail $constraint */

      if (null === $value || '' === $value) {
         return;
      }

      if (!is_string($value)) {
         throw new UnexpectedTypeException($value, 'string');
      }

      if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
         return;
      }

      $cleanedPhone = preg_replace('/[\s\-]/', '', $value);

      if (preg_match('/^\+\d{8,15}$/', $cleanedPhone)) {
         return;
      }

      $this->context->buildViolation($constraint->message)
         ->setParameter('{{ value }}', $this->formatValue($value))
         ->addViolation();
   }
}
