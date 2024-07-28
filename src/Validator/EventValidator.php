<?php

namespace App\Validator;

class EventValidator
{
    /**
     * Validate event data.
     *
     * @param array $data The form data.
     * @return array An array of errors, if any.
     */
    public function validate(array $data): array
    {
        $errors = [];

        // Validate Event Summary
        $summary = trim($data['summary'] ?? '');
        if (strlen($summary) < 5 || strlen($summary) > 100) {
            $errors['summary'] = 'Summary must be between 5 and 100 characters.';
        }

        // Validate Location
        $location = trim($data['location'] ?? '');
        if (strlen($location) > 255) {
            $errors['location'] = 'Location cannot exceed 255 characters.';
        }

        // Validate Description
        $description = trim($data['description'] ?? '');
        if (strlen($description) > 500) {
            $errors['description'] = 'Description cannot exceed 500 characters.';
        }

        // Validate Start and End Date/Time
        $startDatetime = $data['start_datetime'] ?? '';
        $endDatetime = $data['end_datetime'] ?? '';

        if (empty($startDatetime) || empty($endDatetime)) {
            $errors['datetime'] = 'Start and end date/time cannot be empty.';
        } else {
            try {
                $startDate = new \DateTime($startDatetime);
                $endDate = new \DateTime($endDatetime);

                if ($startDate >= $endDate) {
                    $errors['datetime'] = 'End date and time must be after the start date and time.';
                }
            } catch (\Exception $e) {
                $errors['datetime'] = 'Invalid date/time format.';
            }
        }

        // Validate Time Zone
        $timezone = $data['timezone'] ?? '';
        if (empty($timezone) || !in_array($timezone, \DateTimeZone::listIdentifiers())) {
            $errors['timezone'] = 'Please select a valid time zone.';
        }

        return $errors;
    }
}

