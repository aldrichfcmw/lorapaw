<?php

require_once("./api/telebot.php");
require_once("./api/get-data.php");

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
    $data = getLastData();
    $pesan = "Kesehatan Kucing: <br> " .
        " Suhu  : " . $data['temp'] . " C <br>" .
        " Denyut: " . $data['bpm'] . " Bpm <br>" .
        " Status: " . $data['status'];
    $ctx->replyWithText($pesan);
});

$bot->command("cek_lokasi", function ($ctx) {
    $data = getLastData();
    $pesan = "Lokasi Kucing: <br> " .
        " Latitude  : " . $data['latitude'] . " <br>" .
        " Longitude : " . $data['logitude'] . " <br>" .
        " Links     :  https://www.google.com/maps/@" . $data['latitude'] . "," . $data['latitude'] . ",15z";
    $ctx->replyWithText($pesan);
});

// run bot
$bot->run();
