<?php

namespace App\Controller;

use App\Service\EventService;
use App\Validator\EventValidator;
use DateTime;
use DateTimeZone;
use Exception;
use Google_Service_Exception;

class EventController
{
    private $eventValidator;
    private $eventService;

    public function __construct(EventService $eventService, EventValidator $eventValidator)
    {
        $this->eventService = $eventService;
        $this->eventValidator = $eventValidator;
    }

    public function index()
    {

        session_start();

        try {
            if ($this->handleOAuthCallback()) {
                return;
            }

            if (!$this->isAuthenticated()) {
                $this->displayAuthLink();
                return;
            }

            $this->eventService->setAccessToken();

            try {
                $events = $this->eventService->list();
            } catch (Exception $e) {
                $this->displayError("Error fetching events: " . $e->getMessage());
                return;
            }

            require __DIR__ . '/../View/list-events.php';

        } catch (Google_Service_Exception $e) {
            $this->displayError("Google Service Error: " . $e->getMessage());
        } catch (Exception $e) {
            $this->displayError("An unexpected error occurred: " . $e->getMessage());
        }
    }

    private function handleOAuthCallback(): bool
    {
        if (isset($_GET['code'])) {
            try {
                $this->eventService->authenticate($_GET['code']);
                $this->redirectToIndex();
            } catch (Exception $e) {
                $this->displayError("OAuth Error: " . $e->getMessage());
            }
            return true;
        }
        return false;
    }

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['access_token']);
    }

    private function displayAuthLink(): void
    {
        $authUrl = $this->eventService->createAuthUrl();
        echo "<a href='" . htmlspecialchars($authUrl) . "'>Connect Google Calendar</a>";
    }

    private function displayError(string $message): void
    {
        echo "<div class='error'>" . htmlspecialchars($message) . "</div>";
    }

    private function redirectToIndex(): void
    {
        header('Location: index.php');
        exit;
    }

    public function createEvent()
    {
        session_start();

        if (!$this->isAuthenticated()) {
            $this->redirectToIndex();
        }

        $this->eventService->setAccessToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this->eventValidator->validate($_POST);

            if (empty($errors)) {
                $eventData = $this->prepareEventData($_POST);

                try {
                    $this->eventService->create($eventData);
                    $this->redirectToIndex();
                } catch (Exception $e) {
                    $this->displayError('Error creating event: ' . $e->getMessage());
                }
            } else {
                $this->handleValidationErrors($errors, $_POST);
            }
        }

        $this->renderCreateEventForm();
    }

    private function prepareEventData(array $data): array
    {
        $summary = trim($data['summary']);
        $location = trim($data['location']);
        $description = trim($data['description']);
        $startDatetime = $data['start_datetime'];
        $endDatetime = $data['end_datetime'];
        $timezone = $data['timezone'];

        // Validate and set default timezone if empty
        if (empty($timezone) || !in_array($timezone, DateTimeZone::listIdentifiers())) {
            $timezone = 'UTC';
        }

        $start = new DateTime($startDatetime, new DateTimeZone($timezone));
        $end = new DateTime($endDatetime, new DateTimeZone($timezone));

        return [
            'summary' => $summary,
            'location' => $location,
            'description' => $description,
            'start' => [
                'dateTime' => $start->format('Y-m-d\TH:i:sP'),
                'timeZone' => $timezone,
            ],
            'end' => [
                'dateTime' => $end->format('Y-m-d\TH:i:sP'),
                'timeZone' => $timezone,
            ],
        ];
    }

    private function handleValidationErrors(array $errors, array $formData): void
    {
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = $formData;
        header('Location: index.php?action=create');
        exit;
    }

    private function renderCreateEventForm(): void
    {
        $formData = $_SESSION['formData'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['formData'], $_SESSION['errors']);
        require __DIR__ . '/../View/create-event.php';
    }

    public function deleteEvent()
    {
        session_start();

        if (!$this->isAuthenticated()) {
            $this->redirectToIndex();
        }

        $this->eventService->setAccessToken();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
            $eventId = $_POST['event_id'];

            try {
                $this->eventService->delete($eventId);
                $this->redirectToIndex();
            } catch (Exception $e) {
                $this->displayError('Error deleting event: ' . $e->getMessage());
            }
        }

        require __DIR__ . '/../View/delete-event.php';
    }

    public function disconnect()
    {
        session_start();

        $this->eventService->disconnect();

        $this->redirectToIndex();
    }
}
