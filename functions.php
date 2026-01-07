<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config_env.php';

// ----------------------
// Prüft Admin-Login
// ----------------------
function checkAdmin() {
    global $db;
    if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
        header("Location: index.php");
        exit;
    }
}

// ----------------------
// Token generieren
// ----------------------
function generateToken($length = 12){
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
}

// ----------------------
// PDF-Mindestgröße prüfen (erste Seite)
// ----------------------
function checkPDFSize($filePath, $minWidthMM, $minHeightMM){
    require_once __DIR__ . '/vendor/autoload.php'; // FPDI
    $pdf = new \setasign\Fpdi\Fpdi();
    $pageCount = $pdf->setSourceFile($filePath);
    $tpl = $pdf->importPage(1);
    $size = $pdf->getTemplateSize($tpl);

    $width = $size['width'];
    $height = $size['height'];

    return ($width >= $minWidthMM && $height >= $minHeightMM);
}

// ----------------------
// SQL-Datei importieren (beim ersten Start)
// ----------------------
function importSQL($sqlFile){
    global $db;
    $stmt = $db->query("SHOW TABLES LIKE 'admin'");
    if($stmt->rowCount() == 0){
        $sql = file_get_contents($sqlFile);
        $db->exec($sql);
    }
}

// Import direkt ausführen
importSQL
