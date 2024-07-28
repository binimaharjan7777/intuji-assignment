<?php

namespace App\Interface;

/**
 * Interface EventAuthInterface
 *
 * Provides methods for authenticating with a calendar event service.
 */
interface EventAuthInterface
{
   public  function authenticate($code);

   public function createAuthUrl();

   public function disconnect();
}
