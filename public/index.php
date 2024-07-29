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


try {
    // Instantiate the dependencies
    $eventService = new EventService();
    $eventValidator = new EventValidator();
    // Instantiate the controller with dependencies
    $controller = new EventController($eventService, $eventValidator);
} catch (Exception $e) {
    displayCriticalError(htmlspecialchars($e->getMessage()));
    exit;
}


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
    displayCriticalError(htmlspecialchars($e->getMessage()));

    echo '<div class="error">An unexpected error occurred: ' . htmlspecialchars($e->getMessage()) . '</div>';
}
/**
 * Function to display a critical error message
 *
 * @param string $message The error message to be displayed.
 */
function displayCriticalError(string $message)
{
    // Optionally, redirect to a custom error page instead of displaying the error inline
    // header('Location: error.php?msg=' . urlencode($message));
    // exit;

    echo '<div class="error" style="color: red; background: #fdd; padding: 10px; border-radius: 5px;">';
    echo '<strong>Error:</strong> ' . htmlspecialchars($message);
    echo '</div>';
}
