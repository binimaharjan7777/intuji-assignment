<?php

return [
    'google' => [
        'application_name' => 'Google Calendar App',
        'credentials_path' => __DIR__ . '/credentials.json',
        'redirect_uri' => 'http://localhost:8000',
        'scopes' => [Google_Service_Calendar::CALENDAR],
    ],
];
