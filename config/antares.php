<?php
// URL API dan API Key dari Antares
$antaresUrl = 'https://platform.antares.id:8443/~/antares-cse/antares-id/lorapaw/pawpaw/la';
$apiKey = '8fde35f36ed0462e:a14424236b4a82a9'; // Ganti dengan API key Anda

// Fungsi untuk mengambil data dari Antares
function fetchDataFromAntares($url, $apiKey)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-M2M-Origin: ' . $apiKey,
        'Content-Type: application/json;ty=4',
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        echo "Error fetching data from Antares: " . curl_error($ch);
        exit;
    }

    curl_close($ch);
    return $response;
}

// Fungsi untuk mem-parse data JSON yang diterima dari Antares
function parseAntaresData($jsonString)
{
    $data = json_decode($jsonString, true);

    if (!isset($data['m2m:cin'])) {
        echo "Invalid data format.";
        exit;
    }

    $conData = json_decode($data['m2m:cin']['con'], true);
    return $conData;
}

// Fungsi untuk mem-parse data nested di dalam 'data' field
function parseNestedData($rawDataString)
{
    $rawDataString = trim($rawDataString, '{}');
    $rawDataArray = explode(',', $rawDataString);

    $nestedData = [];
    foreach ($rawDataArray as $pair) {
        list($key, $value) = explode(':', $pair);
        $nestedData[trim($key)] = trim($value);
    }

    return $nestedData;
}
