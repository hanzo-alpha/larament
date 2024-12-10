<?php

use NiftyCo\Skeletor\Skeletor;

return function (Skeletor $skeletor) {
    $skeletor->intro('Welcome to Larament setup! Let\'s get started.');

    $applicationName = $skeletor->text('What is the application name?', 'Laravel', required: true);
    $applicationDescription = $skeletor->text('What is the application description?', 'A cool Laravel application');
    $timezone = $skeletor->search(
        'Which timezone would you like to use?',
        fn (string $query) => collect(timezone_identifiers_list())
            ->filter(fn (string $timezone) => str_contains(strtolower($timezone), strtolower($query)))
            ->values()
            ->all()
    );

    $skeletor->intro('Let\'s setup the default user that will be created.');

    $name = $skeletor->text('What is the demo username?', 'John Doe', required: true);
    $email = $skeletor->text('What is the demo email?', 'admin@example.com', required: true);
    $password = $skeletor->password('What is the demo password?', 'password', required: true);

    // If the user entered a name, replace the APP_NAME value in the .env file
    if ($applicationName) {
        $skeletor->pregReplaceInFile('/^APP_NAME=(.*)$/m', 'APP_NAME="'.$applicationName.'"', '.env');
    }

    // If the user entered a description, replace the description value in the composer.json file
    if ($applicationDescription) {
        $skeletor->pregReplaceInFile(
            '/"description":\s*".*?"/',
            '"description": "'.addslashes($applicationDescription).'"',
            'composer.json'
        );
    }

    // If the user entered a name, replace the DEFAULT_USER_NAME value in the .env file
    if ($name) {
        $skeletor->pregReplaceInFile('/^DEFAULT_USER_NAME=(".*?"|[^"\s]*|)$/m', 'DEFAULT_USER_NAME="'.$name.'"', '.env');
    }

    // If the user entered an email, replace the DEFAULT_USER_EMAIL value in the .env file
    if ($email) {
        $skeletor->pregReplaceInFile('/^DEFAULT_USER_EMAIL=(".*?"|[^"\s]*|)$/m', 'DEFAULT_USER_EMAIL="'.$email.'"', '.env');
    }

    // If the user entered a password, replace the DEFAULT_USER_PASSWORD value in the .env file
    if ($password) {
        $skeletor->pregReplaceInFile('/^DEFAULT_USER_PASSWORD=(".*?"|[^"\s]*|)$/m', 'DEFAULT_USER_PASSWORD="'.$password.'"', '.env');
    }

    // If the user entered a timezone, replace the APP_TIMEZONE value in the .env file
    if ($timezone) {
        $skeletor->pregReplaceInFile('/^APP_TIMEZONE=(".*?"|[^"\s]*|)$/m', 'APP_TIMEZONE="'.$timezone.'"', '.env');
    }

    if ($skeletor->exists('README.md')) {
        $skeletor->removeFile('README.md');
    }

    if ($skeletor->exists('resources/images/larament.png')) {
        $skeletor->removeFile('resources/images/larament.png');
    }

    if ($skeletor->exists('resources/images/pest-php.png')) {
        $skeletor->removeFile('resources/images/pest-php.png');
    }

    if ($skeletor->exists('resources/images/user-global-search.jpg')) {
        $skeletor->removeFile('resources/images/user-global-search.jpg');
    }

    if ($skeletor->exists('resources/images/global-search-keybinding.jpg')) {
        $skeletor->removeFile('resources/images/global-search-keybinding.jpg');
    }

    $skeletor->outro('Setup completed! Enjoy your new Laravel and FilamentPHP application.');
};
