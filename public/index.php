<?php

// Report all PHP errors and display them on the screen
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Autoload all the classes and dependencies via Composer
require __DIR__ . '/../vendor/autoload.php';

// Use the required namespaces
use App\Controller\EventController;
use App\Service\EventService;
use App\Validator\EventValidator;

// Instantiate the dependencies
$eventService = new EventService();
$eventValidator = new EventValidator();

// Instantiate the controller with dependencies
$controller = new EventController($eventService, $eventValidator);

// Sanitize the action parameter
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'index';

// Use a try-catch block to handle any unexpected errors
try {
    switch ($action) {
        case 'create':
            $controller->createEvent();
            break;
        case 'delete':
            $controller->deleteEvent();
            break;
        case 'disconnect':
            $controller->disconnect();
            break;
        default:
            $controller->index();
            break;
    }
} catch (Exception $e) {
    // Display the error message in case of an exception
    echo '<div class="error">An unexpected error occurred: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
