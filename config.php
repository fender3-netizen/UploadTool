<?php
session_start();

$host = 'localhost';      // MySQL Server
$dbname = 'pdf_upload';   // Datenbankname
$user = 'root';            // MySQL Benutzer
$pass = 'meinPasswort';    // MySQL Passwort

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tabellen erstellen, falls nicht vorhanden
    $db->exec("
        CREATE TABLE IF NOT EXISTS admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50),
            password VARCHAR(255)
        );
        CREATE TABLE IF NOT EXISTS config (
            id INT AUTO_INCREMENT PRIMARY KEY,
            width FLOAT,
            height FLOAT
        );
        CREATE TABLE IF NOT EXISTS links (
            id INT AUTO_INCREMENT PRIMARY KEY,
            token VARCHAR(50)
        );
    ");

    // Default Admin anlegen, falls nicht vorhanden
    $stmt = $db->query("SELECT COUNT(*) FROM admin");
    if($stmt->fetchColumn() == 0){
        $db->exec("INSERT INTO admin (username,password) VALUES ('admin','admin123')");
    }

} catch(PDOException $e){
    die("DB Error: ".$e->getMessage());
}
?>
