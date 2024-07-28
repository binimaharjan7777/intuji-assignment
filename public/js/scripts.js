// js/scripts.js

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('event-form');

    const fields = {
        summary: {
            element: document.getElementById('summary'),
            validate: function (value) {
                if (value.trim() === '') {
                    return 'Event summary is required.';
                }
                return '';
            }
        },
        location: {
            element: document.getElementById('location'),
            validate: function (value) {
                if (value.trim() === '') {
                    return 'Location is required.';
                }
                return '';
            }
        },
        description: {
            element: document.getElementById('description'),
            validate: function (value) {
                if (value.trim() === '') {
                    return 'Description is required.';
                }
                return '';
            }
        },
        start_datetime: {
            element: document.getElementById('start_datetime'),
            validate: function (value) {
                if (value === '') {
                    return 'Start date and time are required.';
                }
                return '';
            }
        },
        end_datetime: {
            element: document.getElementById('end_datetime'),
            validate: function (value) {
                if (value === '') {
                    return 'End date and time are required.';
                }
                // Check if end datetime is after start datetime
                const startValue = fields.start_datetime.element.value;
                if (startValue && new Date(value) <= new Date(startValue)) {
                    return 'End date and time must be after the start date and time.';
                }
                return '';
            }
        },
        timezone: {
            element: document.getElementById('timezone'),
            validate: function (value) {
                if (value === '') {
                    return 'Timezone is required.';
                }
                return '';
            }
        }
    };

    // Function to validate each field
    function validateField(field) {
        const errorElement = field.element.nextElementSibling;
        const errorMessage = field.validate(field.element.value);
        if (errorMessage) {
            errorElement.innerHTML = errorMessage;
            field.element.classList.add('border-red-500');
            errorElement.style.display = 'block';
            return false;
        } else {
            errorElement.innerHTML = '';
            field.element.classList.remove('border-red-500');
            errorElement.style.display = 'none';
            return true;
        }
    }

    // Add event listeners for each field for real-time validation
    for (const key in fields) {
        const field = fields[key];
        field.element.addEventListener('input', function () {
            validateField(field);
        });
        field.element.addEventListener('blur', function () {
            validateField(field);
        });
    }

    // Validate all fields before submitting
    form.addEventListener('submit', function (event) {
        let isValid = true;
        for (const key in fields) {
            if (!validateField(fields[key])) {
                isValid = false;
                fields[key].element.focus(); // Focus on the first invalid field
                break;
            }
        }
        if (!isValid) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});
