<?php

require_once __DIR__ . '../../../../vendor/autoload.php';

function formatNiceDate($dateString) {
    // Create a DateTime object from the input date string
    $date = new DateTime($dateString);

    // Format the date in the desired format (e.g., December 12, 2024)
    $niceFormat = $date->format('j F, Y');

    return $niceFormat;
}

function interpretServiceVariable($serviceType){
    $service = '';
    switch($serviceType){
        case 'wof':
            $service = 'Warrant of Fitness';
        case 'oil':
            $service = 'Oil Change';
        case 'wheel':
            $service = 'Wheel Alignment';
    }
    return $service;
}

function get_google_client_and_service($email, $date, $serviceType, $name, $phone)
{
    $tokenPath = 'C:\Users\mathe\Local Sites\matts-test-site\app\public\wp-content\themes\bizboost-child\client_secret.json';
    $refreshToken = '1//04uDloxdG7MlrCgYIARAAGAQSNwF-L9Irt4BtBpBVxfWCshKiIR6ET2yvp4XPesV8ROrN62aR-M1iZ5G4x-MOX-yjQi1QBDyIDaw';
    $client = new Google_Client();
    $client->setAuthConfig($tokenPath);
    $client->setScopes(Google_Service_Gmail::GMAIL_SEND);
    $client->setAccessType('offline');

    $client->fetchAccessTokenWithRefreshToken($refreshToken);
    $accessToken = $client->getAccessToken();
    $client->setAccessToken($accessToken);

    $service = new Google_Service_Gmail($client);

    $message = new Google_Service_Gmail_Message();
    $rawMessage = base64_encode("To: " .$email. "\r\nSubject: Matty's Auto Service Booking\r\n\r\nThank you for your booking request! We have been notified about your "
    . interpretServiceVariable($serviceType) . " booking on the " . formatNiceDate($date) . ". You'll hear back from us shortly");



    $message->setRaw($rawMessage);
    $service->users_messages->send('me', $message);

    $message2 = new Google_Service_Gmail_message();
    $rawMessage2 = base64_encode("To: mattysautoservices@gmail.com\r\nSubject: New Booking Request: " . $serviceType . "\r\n\r\nNew booking request recieved: \nName: " . $name. "\nEmail: " . $email."\nPhone: " . $phone . "\nDate: " . formatNiceDate($date). "\nService Type: " . interpretServiceVariable($serviceType) . "\n");
    $message2->setRaw($rawMessage2);
    $service->users_messages->send('me', $message2);
}

