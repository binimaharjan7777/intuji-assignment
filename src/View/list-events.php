<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Calendar Events</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>

<body class="bg-gray-100">

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Google Calendar Events</h1>

        <!-- Create Event and Disconnect Buttons -->
        <div class="mb-4">
            <a href="index.php?action=create" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow-md">
                Create Event
            </a>
            <a href="index.php?action=disconnect" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow-md">
                Disconnect
            </a>
        </div>

        <!-- Events Table -->
        <div class="overflow-x-auto">
            <table class="table-auto w-full bg-white shadow-md rounded">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 text-left">Event</th>
                        <th class="px-4 py-2 text-left">Location</th>
                        <th class="px-4 py-2 text-left">Time Zone</th>
                        <th class="px-4 py-2 text-left">Start</th>
                        <th class="px-4 py-2 text-left">End</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($events)) : ?>
                        <?php foreach ($events as $event) : ?>
                            <tr class="border-t border-gray-300 hover:bg-gray-100">
                                <td class="border px-4 py-2">
                                    <?= htmlspecialchars($event->getSummary() ?? 'No Title') ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?= htmlspecialchars($event->getLocation() ?? 'No Location') ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?= htmlspecialchars($event->getStart()->getTimeZone() ?? 'No Time Zone') ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?= htmlspecialchars($event->getStart()->getDateTime() ?? $event->getStart()->getDate()) ?>
                                </td>
                                <td class="border px-4 py-2">
                                    <?= htmlspecialchars($event->getEnd()->getDateTime() ?? $event->getEnd()->getDate()) ?>
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    <form action="index.php?action=delete" method="post" class="inline-block">
                                        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event->getId()) ?>">
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow-md">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-600">
                                No events found. Please create a new event.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
