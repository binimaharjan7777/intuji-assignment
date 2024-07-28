<?php

namespace App\Interface;

/**
 * Interface EventInterface
 *
 * This interface defines the core operations for managing events.
 */
interface EventInterface
{
   public  function create($data);
   public  function list();
   public  function delete($eventId);
}
