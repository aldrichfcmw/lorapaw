<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('/home/drik-lorapaw/htdocs/lorapaw.drik.my.id/config/database_connection.php');
include('/home/drik-lorapaw/htdocs/lorapaw.drik.my.id/config/antares.php');

// Fungsi untuk mendapatkan counter terakhir dari database
function getLastDate($pdo)
{
    $stmt = $pdo->prepare("SELECT tgl FROM antares_data ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($result !== false) ? $result['tgl'] : null;
}

// Fungsi untuk menyimpan data ke database
function saveDataToDatabase($pdo, $counter, $cValue, $bValue, $laValue, $loValue, $sValue, $tgl)
{
    $stmt = $pdo->prepare("INSERT INTO antares_data (counter, temp, bpm, latitude, longitude, status, tgl) VALUES (:counter, :C, :B, :La, :Lo, :S, :Tgl )");
    $stmt->bindParam(':counter', $counter);
    $stmt->bindParam(':C', $cValue);
    $stmt->bindParam(':B', $bValue);
    $stmt->bindParam(':La', $laValue);
    $stmt->bindParam(':Lo', $loValue);
    $stmt->bindParam(':S', $sValue);
	$stmt->bindParam(':Tgl', $tgl);
    $stmt->execute();
}

// Mengambil data dari Antares
$jsonString = fetchDataFromAntares($antaresUrl, $apiKey);
//print_r($jsonString);

//parse data
$jsonData = json_decode($jsonString, true);
$m2mData = $jsonData['m2m:cin'];
$dateTime = $m2mData['lt'];

// Buat objek DateTime dari format yang sesuai
$date = DateTime::createFromFormat('Ymd\THis', $dateTime);

// Tampilkan dalam format yang diinginkan
$tgl= $date->format('Y-m-d H:i:s');

// Mem-parse data dari Antares
$conData = parseAntaresData($jsonString);
//print_r($conData);

// Ambil nilai counter dari data
// $countValue = $conData['counter'];
$countValue = '';
// echo $countValue . "<br>";

// Mem-parse data nested di dalam 'data' field
// $nestedData = parseNestedData($conData['data']);

// Ambil nilai-nilai yang diinginkan
$cValue = $conData['C'];
$bValue = $conData['B'];
$laValue = $conData['La'];
$loValue = $conData['Lo'];
$sValue = ($conData['S'] == "Sehat") ? "Kucing Sehat" : "Kucing Sakit";


// Koneksi ke database
$pdo = connectToDatabase();

// Dapatkan counter terakhir dari database
$lastDate = getLastDate($pdo);	
//print_r($lastDate)
//echo $lastDate


// Tampilkan data yang berhasil diambil dari Antares
 echo "Data dari Antares: <br>";
 echo "Count     : $countValue" . "<br>";
 echo "Temp      : $cValue" . "<br>";
 echo "BPM       : $bValue"  . "<br>";
 echo "Latitude  : $laValue" . "<br>";
 echo "Longitude : $loValue" . "<br>";
 echo "Status    : $sValue" . "<br>";
 echo "Tanggal   : $tgl" . "<br>";
 echo "Tgl Akhir : $lastDate" . "<br>";

// Bandingkan counter dengan data terbaru
if ($lastDate !== null && $lastDate == $tgl) {
	 echo "<br>" . "Data sama dengan yang tersimpan di database. Dilewatkan.";
} else {
    // Jika counter berbeda atau data belum tersimpan, simpan ke database
    saveDataToDatabase($pdo, $countValue, $cValue, $bValue, $laValue, $loValue, $sValue, $tgl);
    echo "<br>" . "Data berhasil disimpan ke database.";
}
//saveDataToDatabase($pdo, $countValue, $cValue, $bValue, $laValue, $loValue, $sValue);
//echo "<br>" . "Data berhasil disimpan ke database.";