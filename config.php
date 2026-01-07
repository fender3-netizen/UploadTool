<?php
session_start();

// SQLite DB
$dbFile = __DIR__ . '/data/db.sqlite';
if(!file_exists(__DIR__ . '/data')) mkdir(__DIR__ . '/data', 0755, true);
if(!file_exists(__DIR__ . '/uploads')) mkdir(__DIR__ . '/uploads', 0755, true);

try {
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Tabellen erstellen
    $db->exec("CREATE TABLE IF NOT EXISTS admin (username TEXT, password TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS config (id INTEGER PRIMARY KEY, width REAL, height REAL)");
    $db->exec("CREATE TABLE IF NOT EXISTS links (id INTEGER PRIMARY KEY, token TEXT)");

    // Default Admin
    $stmt = $db->query("SELECT COUNT(*) FROM admin");
    if($stmt->fetchColumn() == 0){
        $db->exec("INSERT INTO admin (username,password) VALUES ('admin','admin123')");
    }
} catch(PDOException $e){
    die("DB Error: ".$e->getMessage());
}
?>
