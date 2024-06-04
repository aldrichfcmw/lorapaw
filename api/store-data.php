<?php
include('../config/database_connection.php');
include('../config/antares.php');

// Fungsi untuk mendapatkan counter terakhir dari database
function getLastCounter($pdo)
{
    $stmt = $pdo->prepare("SELECT counter FROM antares_data ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return ($result !== false) ? $result['counter'] : null;
}

// Fungsi untuk menyimpan data ke database
function saveDataToDatabase($pdo, $cValue, $bValue, $laValue, $loValue, $sValue, $counter)
{
    $stmt = $pdo->prepare("INSERT INTO antares_data (counter, temp, bpm, latitude, longitude, status) VALUES (:C, :B, :La, :Lo, :S, :counter)");
    $stmt->bindParam(':counter', $counter);
    $stmt->bindParam(':C', $cValue);
    $stmt->bindParam(':B', $bValue);
    $stmt->bindParam(':La', $laValue);
    $stmt->bindParam(':Lo', $loValue);
    $stmt->bindParam(':S', $sValue);
    $stmt->execute();
}

// Mengambil data dari Antares
$jsonString = fetchDataFromAntares($antaresUrl, $apiKey);

// Mem-parse data dari Antares
$conData = parseAntaresData($jsonString);

// Ambil nilai counter dari data
$counter = $conData['counter'];

// Mem-parse data nested di dalam 'data' field
$nestedData = parseNestedData($conData['data']);

// Ambil nilai-nilai yang diinginkan
$cValue = $nestedData['C'];
$bValue = $nestedData['B'];
$laValue = $nestedData['La'];
$loValue = $nestedData['Lo'];
$sValue = $nestedData['S'];

// Tampilkan data yang berhasil diambil dari Antares
// echo "Data dari Antares:\n";
// echo "C: $cValue\n";
// echo "B: $bValue\n";
// echo "La: $laValue\n";
// echo "Lo: $loValue\n";
// echo "S: $sValue\n";

// Koneksi ke database
$pdo = connectToDatabase();

// Dapatkan counter terakhir dari database
$lastCounter = getLastCounter($pdo);

// Bandingkan counter dengan data terbaru
if ($lastCounter !== null && $lastCounter == $counter) {
    echo "<br>" . "Data sama dengan yang tersimpan di database. Dilewatkan.";
} else {
    // Jika counter berbeda atau data belum tersimpan, simpan ke database
    saveDataToDatabase($pdo, $cValue, $bValue, $laValue, $loValue, $sValue, $counter);
    echo "<br>" . "Data berhasil disimpan ke database.";
}
