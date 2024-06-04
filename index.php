<?php

require_once("./telebot.php");

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

// run bot
$bot->run();