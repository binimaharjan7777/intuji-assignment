<?php

namespace App\Util;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

/**
 * Class GoogleCalendarUtil
 *
 * A utility class for managing Google Calendar API interactions.
 */
class GoogleCalendarUtil
{
    /**
     * @var GoogleCalendarUtil|null Singleton instance.
     */
    private static $instance = null;

    /**
     * @var Google_Client Google API client.
     */
    private $client;

    /**
     * @var Google_Service_Calendar Google Calendar service instance.
     */
    private $calendarService;

    /**
     * GoogleCalendarUtil constructor.
     *
     * Initializes the Google API client and Google Calendar service.
     */
    private function __construct()
    {
        $this->client = new Google_Client();
        $config = require __DIR__ . '/../../config/config.php';

        $this->client->setApplicationName($config['google']['application_name']);
        $this->client->setAuthConfig($config['google']['credentials_path']);
        $this->client->setRedirectUri($config['google']['redirect_uri']);
        $this->client->setScopes($config['google']['scopes']);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        $this->calendarService = new Google_Service_Calendar($this->client);
    }

    /**
     * Get the singleton instance of GoogleCalendarUtil.
     *
     * @return GoogleCalendarUtil The singleton instance.
     */
    public static function getInstance(): GoogleCalendarUtil
    {
        if (self::$instance === null) {
            self::$instance = new GoogleCalendarUtil();
        }

        return self::$instance;
    }

    /**
     * Get the Google API client.
     *
     * @return Google_Client The Google API client.
     */
    public function getClient(): Google_Client
    {
        return $this->client;
    }

    /**
     * Authenticate the user with Google using an authorization code.
     *
     * @param string $code The authorization code.
     * @throws \Exception If authentication fails.
     */
    public function authenticate(string $code): void
    {
        $this->wrapExceptions(function () use ($code) {
            $client = $this->getClient();
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($accessToken['error'])) {
                throw new \Exception('Failed to fetch access token: ' . $accessToken['error']);
            }

            $_SESSION['access_token'] = $accessToken;
            $client->setAccessToken($accessToken);
        });
    }

    /**
     * Check if the user is authenticated.
     *
     * @return bool True if authenticated, false otherwise.
     */
    public function isAuthenticated(): bool
    {
        return isset($_SESSION['access_token']) && !empty($_SESSION['access_token']);
    }

    /**
     * Disconnect the user by clearing the access token.
     */
    public function disconnect(): void
    {
        unset($_SESSION['access_token']);
    }

    /**
     * Get the current access token.
     *
     * @return string|null The access token, or null if not set.
     */
    public function getAccessToken(): ?string
    {
        return $_SESSION['access_token'] ?? null;
    }

    /**
     * Set the access token for the Google API client.
     *
     * @param array|string $token The access token.
     */
    public function setAccessToken($token): void
    {
        if (!empty($token)) {
            $this->client->setAccessToken($token);
        }
    }

    /**
     * List events from a Google Calendar.
     *
     * @param string $calendarId The calendar ID, defaults to 'primary'.
     * @param int $maxResults The maximum number of results to return, defaults to 10.
     * @return array The list of calendar events.
     * @throws \Exception If fetching events fails.
     */
    public function list(string $calendarId = 'primary', int $maxResults = 10): array
    {
        return $this->wrapExceptions(function () use ($calendarId, $maxResults) {
            $optParams = [
                'maxResults' => $maxResults,
                'orderBy' => 'startTime',
                'singleEvents' => true,
                'timeMin' => date('c'),
            ];

            $results = $this->calendarService->events->listEvents($calendarId, $optParams);
            return $results->getItems();
        });
    }

    /**
     * Create a new event in a Google Calendar.
     *
     * @param array $eventData The event data.
     * @param string $calendarId The calendar ID to add the event to.
     * @return \Google_Service_Calendar_Event The created event.
     * @throws \Exception If event creation fails.
     */
    public function create(array $eventData, string $calendarId): \Google_Service_Calendar_Event
    {
        return $this->wrapExceptions(function () use ($eventData, $calendarId) {
            $event = new Google_Service_Calendar_Event($eventData);
            return $this->calendarService->events->insert($calendarId, $event);
        });
    }

    /**
     * Delete an event from a Google Calendar.
     *
     * @param string $eventId The ID of the event to delete.
     * @param string $calendarId The calendar ID from which to delete the event, defaults to 'primary'.
     * @throws \Exception If event deletion fails.
     */
    public function delete(string $eventId, string $calendarId = 'primary'): void
    {
        $this->wrapExceptions(function () use ($eventId, $calendarId) {
            $this->calendarService->events->delete($calendarId, $eventId);
        });
    }

    /**
     * Create an authentication URL for Google Calendar.
     *
     * @return string The authentication URL.
     */
    public function createAuthUrl(): string
    {
        return $this->client->createAuthUrl();
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
            throw new \Exception('An error occurred in GoogleCalendarUtil: ' . $e->getMessage());
        }
    }
}
