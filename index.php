<?php
require_once("./config/database_connection.php");
require_once("./api/telebot.php");


// initialize bot
$bot = new Telebot("7465950918:AAExYVr4dM1Hwpo0rPBiw_4Fy1rKHAqrmyY");

// handle start command
$bot->command("start", function ($ctx) {
    $ctx->replyWithText("Kamu mengirimkan command /start");
});

// handle hello command
$bot->command("hello", function ($ctx) {
    $ctx->replyWithText("Halo kak " . $ctx->from->first_name);
});

$bot->command("cek_kesehatan", function ($ctx) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare("SELECT * FROM antares_data ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $pesan = "Kesehatan Kucing: \n" .
        " - Tanggal \t: " . $data['created_at'] . "\n" .
        " - Suhu \t \t \t : " . $data['temp'] . " C \n" .
        " - Denyut \t \t: " . $data['bpm'] . " Bpm \n" .
        " - Status \t \t : " . $data['status'];
    $ctx->replyWithText($pesan);
});

$bot->command("cek_lokasi", function ($ctx) {
    $pdo = connectToDatabase();
    $stmt = $pdo->prepare("SELECT * FROM antares_data ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $pesan = "Lokasi Kucing: \n" .
        " - Tanggal \t \t: " . $data['created_at'] . "\n" .
        " - Latitude \t \t: " . $data['latitude'] . " \n" .
        " - Longitude : " . $data['longitude'] . " \n" .
        " - Links : https://www.google.com/maps/search/?api=1&query=" . $data['latitude'] . "," . $data['longitude'];
    $ctx->replyWithText($pesan);
});

// run bot
$bot->run();
