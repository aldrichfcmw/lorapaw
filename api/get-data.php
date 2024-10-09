<?php
include('../config/database_connection.php');

function getLastData()
{
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare("SELECT * FROM antares_data ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($result !== false) ? $result : null;
}

$lastData = getLastData($pdo);
// print_r($lastData);

// Ambil nilai-nilai yang diinginkan
$cValue = $lastData['temp'];
$bValue = $lastData['bpm'];
$laValue = $lastData['latitude'];
$loValue = $lastData['longitude'];
$sValue = $lastData['status'];
$dateValue = $lastData['created_at'];
//$countValue = $lastData['counter'];

// Tampilkan data yang berhasil diambil dari Antares
echo "Data dari Antares: <br>";
echo "Temp      : $cValue" . "<br>";
echo "BPM       : $bValue"  . "<br>";
echo "Latitude  : $laValue" . "<br>";
echo "Longitude : $loValue" . "<br>";
echo "Status    : $sValue" . "<br>";
echo "Date      : $dateValue" . "<br>";
//echo "Counter      : $countValue" . "<br>";
