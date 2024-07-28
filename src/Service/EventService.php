<?php

namespace App\Service;

use App\Interface\EventAuthInterface;
use App\Interface\EventInterface;
use App\Util\GoogleCalendarUtil;

/**
 * Class EventService
 *
 * This class provides functionality to interact with Google Calendar.
 * It implements EventInterface and EventAuthInterface to handle events and authentication.
 */
class EventService implements EventInterface, EventAuthInterface
{
    /**
     * @var GoogleCalendarUtil
     */
    private $googleCalendarUtil;

    /**
     * EventService constructor.
     *
     * Initializes the GoogleCalendarUtil instance.
     */
    public function __construct()
    {
        $this->googleCalendarUtil = GoogleCalendarUtil::getInstance();
    }

    /**
     * Authenticate the user with a given authorization code.
     *
     * @param string $code The authorization code.
     * @throws \Exception If authentication fails.
     */
    public function authenticate($code)
    {
        $this->wrapExceptions(function() use ($code) {
            $this->googleCalendarUtil->authenticate($code);
        });
    }

    /**
     * Create an authentication URL for Google Calendar.
     *
     * @return string The authentication URL.
     * @throws \Exception If URL creation fails.
     */
    public function createAuthUrl()
    {
        return $this->wrapExceptions(function() {
            return $this->googleCalendarUtil->createAuthUrl();
        });
    }

    /**
     * List events from Google Calendar.
     *
     * @return array The list of events.
     * @throws \Exception If fetching events fails.
     */
    public function list()
    {
        return $this->wrapExceptions(function() {
            return $this->googleCalendarUtil->list();
        });
    }

    /**
     * Create a new event in Google Calendar.
     *
     * @param array $data The event data.
     * @return mixed The created event details.
     * @throws \Exception If event creation fails.
     */
    public function create($data)
    {
        return $this->wrapExceptions(function() use ($data) {
            return $this->googleCalendarUtil->create($data, 'primary');
        });
    }

    /**
     * Delete an event from Google Calendar.
     *
     * @param string $eventId The event ID.
     * @throws \Exception If event deletion fails.
     */
    public function delete($eventId)
    {
        $this->wrapExceptions(function() use ($eventId) {
            $this->googleCalendarUtil->delete($eventId, 'primary');
        });
    }

    /**
     * Get the access token from the session.
     *
     * @return string|null The access token, or null if not set.
     */
    public function getAccessToken()
    {
        return $_SESSION['access_token'] ?? null;
    }

    /**
     * Set the access token for Google Calendar API.
     *
     * @throws \Exception If setting the access token fails.
     */
    public function setAccessToken()
    {
        $this->wrapExceptions(function() {
            $accessToken = $this->getAccessToken();
            if ($accessToken !== null) {
                $this->googleCalendarUtil->setAccessToken($accessToken);
            }
        });
    }

    /**
     * Disconnect the user from Google Calendar.
     *
     * @throws \Exception If disconnection fails.
     */
    public function disconnect()
    {
        $this->wrapExceptions(function() {
            $this->googleCalendarUtil->disconnect();
        });
    }

    /**
     * Wrap a callable in a try-catch block to handle exceptions.
     *
     * @param callable $function The function to execute.
     * @return mixed The result of the function.
     * @throws \Exception If the function execution throws an exception.
     */
    private function wrapExceptions(callable $function)
    {
        try {
            return $function();
        } catch (\Exception $e) {
            throw new \Exception('An error occurred: ' . $e->getMessage());
        }
    }
}
