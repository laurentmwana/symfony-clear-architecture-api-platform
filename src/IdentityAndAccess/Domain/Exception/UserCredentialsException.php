<?php

namespace App\IdentityAndAccess\Domain\Exception;

use Exception;

class UserCredentialsException extends Exception
{
   public function __construct(string $message = '')
   {
      $message = $message ?? 'Invalid credentials. Please check your identifier and password.';

      parent::__construct($message);
   }
}
