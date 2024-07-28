<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Google Calendar Event</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Delete Google Calendar Event</h1>

    <form action="index.php?action=delete" method="post" class="bg-white p-4 shadow-md rounded">
        <div class="mb-4">
            <label for="event_id" class="block text-gray-700">Event ID</label>
            <input type="text" name="event_id" id="event_id" class="w-full border-gray-300 rounded mt-1" required>
        </div>

        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete Event</button>
    </form>
</div>

</body>
</html>
