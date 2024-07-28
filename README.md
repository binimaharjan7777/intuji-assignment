Google Calendar Integration Project
This project integrates Google Calendar with a PHP application, allowing users to create and manage events through a web interface. The application is built using Core PHP and utilizes Google Calendar API for managing events.

Table of Contents
Features
Prerequisites
Installation
Configuration
Running the Application
Usage
Troubleshooting
License
Contact


Features
- Authenticate with Google Calendar using OAuth 2.0.
- Create, view, and delete calendar events.
- Real-time form validation with interactive feedback.
- User-friendly interface with Tailwind CSS for styling.
- Custom JavaScript for improved user interactions and validations.


Prerequisites
Before running this project, ensure you have the following installed on your system:

- PHP 7.4+: Download PHP
- Composer: Download Composer
- XAMPP (or any other local server environment): Download XAMPP

Installation
Follow these steps to set up the project on your local machine:
1.Clone the Repository:

git clone https://github.com/your-username/google-calendar-integration.git
cd google-calendar-integration

2. Install Composer Dependencies:

Run the following command to install all the necessary packages using Composer:
composer install

3.Install Node.js and Tailwind CSS (Optional):
If you want to customize the styling further or build Tailwind CSS, ensure you have Node.js installed and run:

npm install
npm run build

4.Create a Google Cloud Project and Enable Google Calendar API:

- Visit the Google Cloud Console.
- Create a new project.
- Navigate to APIs & Services > Library and enable the Google Calendar API.
- Go to Credentials and create an OAuth 2.0 Client ID.
- Download the credentials.json file and place it in the src/config directory.

Configuration
1. Google Calendar Configuration:
Ensure your credentials.json file is in the src/config directory, and your Google Cloud project has the correct OAuth consent screen settings.

Running the Application
1.Start the XAMPP Server:
Open XAMPP Control Panel.
Serve the Application:

If you're using the built-in PHP server,run:

php -S localhost:8000 -t public
Access the application at http://localhost:8000.

Usage
Once the application is running:

1.Connect to Google Calendar:
Click on "Connect Google Calendar" to authenticate.
Authorize the application to access your calendar.


2.Create an Event:
Fill out the form with event details.
Submit the form to create the event on Google Calendar.

3.View Events:
Navigate to the "List Events" page to view your Google Calendar events.

4.Delete Events:
Use the "Delete" button next to each event to remove it from Google Calendar.

Troubleshooting
- Class Not Found Error: Ensure Composer dependencies are installed and autoload is configured correctly.
- Authentication Issues: Verify that the credentials.json file is correct and the Google Cloud project is properly configured.
- CSS/JavaScript Not Loading: Confirm that static assets are correctly linked and accessible in the public directory.
- Time Zone Mismatch: Ensure the server and client time zones are correctly set to avoid discrepancies in event timings.


License
This project is licensed under the MIT License. See the LICENSE file for details.

Contact
For questions or support, please contact:

Your Name: binimaharjan777@gmail.com
GitHub: @binimaharjan7777
