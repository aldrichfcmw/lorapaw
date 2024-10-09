<?php
function connectToDatabase()
{
    //DB Local
    // $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "lorapaw";

    //DB Panel
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "";
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
