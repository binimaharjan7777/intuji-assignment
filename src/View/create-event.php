<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Google Calendar Event</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-gray-100">

<?php
// PHP to handle errors and form data
$errors = $errors ?? [];
$formData = $formData ?? [];

// Function to display errors for a specific field
function displayError($field, $errors) {
    return isset($errors[$field]) ? '<div class="error text-red-500 text-sm mt-1">' . htmlspecialchars($errors[$field]) . '</div>' : '<div class="error text-red-500 text-sm mt-1"></div>';
}

// Function to keep old values in the form fields
function old($field, $formData) {
    return htmlspecialchars($formData[$field] ?? '');
}
?>

<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4 text-center">Create Google Calendar Event</h1>

    <form id="event-form" action="index.php?action=create" method="post" class="space-y-6 bg-white shadow-md rounded p-6">
        <!-- Display error summary for server-side errors -->
        <?php if (!empty($errors)) : ?>
            <div id="error-summary" class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <p class="font-bold">Please fix the errors below:</p>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div>
            <label for="summary" class="block text-gray-700">Event Summary:</label>
            <input type="text" id="summary" name="summary" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 <?php echo isset($errors['summary']) ? 'border-red-500' : ''; ?>" required value="<?php echo old('summary', $formData); ?>">
            <?php echo displayError('summary', $errors); ?> <!-- Display server-side error -->
            <div class="error"></div> <!-- Placeholder for client-side error -->
        </div>

        <div>
            <label for="location" class="block text-gray-700">Location:</label>
            <input type="text" id="location" name="location" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 <?php echo isset($errors['location']) ? 'border-red-500' : ''; ?>" value="<?php echo old('location', $formData); ?>">
            <?php echo displayError('location', $errors); ?> <!-- Display server-side error -->
            <div class="error"></div> <!-- Placeholder for client-side error -->
        </div>

        <div>
            <label for="description" class="block text-gray-700">Description:</label>
            <textarea id="description" name="description" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 <?php echo isset($errors['description']) ? 'border-red-500' : ''; ?>"><?php echo old('description', $formData); ?></textarea>
            <?php echo displayError('description', $errors); ?> <!-- Display server-side error -->
            <div class="error"></div> <!-- Placeholder for client-side error -->
        </div>

        <div>
            <label for="start_datetime" class="block text-gray-700">Start Date & Time:</label>
            <input type="datetime-local" id="start_datetime" name="start_datetime" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 <?php echo isset($errors['start_datetime']) ? 'border-red-500' : ''; ?>" step="1" required value="<?php echo old('start_datetime', $formData); ?>">
            <?php echo displayError('start_datetime', $errors); ?> <!-- Display server-side error -->
            <div class="error"></div> <!-- Placeholder for client-side error -->
        </div>

        <div>
            <label for="end_datetime" class="block text-gray-700">End Date & Time:</label>
            <input type="datetime-local" id="end_datetime" name="end_datetime" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 <?php echo isset($errors['end_datetime']) ? 'border-red-500' : ''; ?>" step="1" required value="<?php echo old('end_datetime', $formData); ?>">
            <?php echo displayError('end_datetime', $errors); ?> <!-- Display server-side error -->
            <div class="error"></div> <!-- Placeholder for client-side error -->
        </div>

        <!-- Time Zone Selection with Search -->
        <div>
            <label for="timezone" class="block text-gray-700">Time Zone:</label>
            <select id="timezone" name="timezone" class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500 <?php echo isset($errors['timezone']) ? 'border-red-500' : ''; ?>" required>
                <option value="">Select a Timezone</option>
                <?php
                // Get a list of all time zones and output them as options
                $timezones = \DateTimeZone::listIdentifiers();
                foreach ($timezones as $timezone) {
                    $selected = old('timezone', $formData) === $timezone ? 'selected' : '';
                    echo "<option value=\"{$timezone}\" {$selected}>{$timezone}</option>";
                }
                ?>
            </select>
            <?php echo displayError('timezone', $errors); ?> <!-- Display server-side error -->
            <div class="error"></div> <!-- Placeholder for client-side error -->
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Create Event</button>
            <a href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded">Cancel</a>
        </div>
    </form>
</div>

<script src="js/scripts.js"></script>
</body>
</html>
