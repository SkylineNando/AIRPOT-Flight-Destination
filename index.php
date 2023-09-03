<?php

// Your Amadeus API credentials
$client_id = '{ HERE }';
$client_secret = '{ HERE }';

// Define the token URL and payload
$token_url = 'https://test.api.amadeus.com/v1/security/oauth2/token';
$token_data = array(
    'grant_type' => 'client_credentials',
    'client_id' => $client_id,
    'client_secret' => $client_secret
);

// Request an access token
$token_response = http_post($token_url, $token_data);
$token_data = json_decode($token_response, true);
$access_token = $token_data['access_token'];

if ($access_token) {
    // Define the flight destinations URL and parameters
    $flight_destinations_url = 'https://test.api.amadeus.com/v1/shopping/flight-destinations';
    $params = array(
        'origin' => 'PAR',     // Origin airport code (e.g., Paris)
        'maxPrice' => '100'   // Maximum price for the flight
    );

    // Set the Authorization header with the access token
    $headers = array(
        'Authorization: Bearer ' . $access_token
    );

    // Make the request to get flight destinations
    $flight_destinations_response = http_get($flight_destinations_url, $params, $headers);

    if ($flight_destinations_response) {
        $flight_destinations_data = json_decode($flight_destinations_response, true);
        echo 'Flight Destinations:' . PHP_EOL;
        print_r($flight_destinations_data);
    } else {
        echo 'Error getting flight destinations' . PHP_EOL;
    }
} else {
    echo 'Failed to obtain access token' . PHP_EOL;
}

// Function to make an HTTP POST request
function http_post($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// Function to make an HTTP GET request
function http_get($url, $params, $headers) {
    $ch = curl_init($url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

?>
