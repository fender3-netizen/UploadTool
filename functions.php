<?php
require_once 'config_env.php';

// Prüft Admin-Login
function checkAdmin() {
    if(!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']){
        header("Location: index.php");
        exit;
    }
}

// Token generieren
function generateToken($length=8){
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length);
}

// Prüft PDF Seitenmaße (erste Seite)
function checkPDFSize($filePath, $minWidthMM, $minHeightMM){
    require_once('vendor/autoload.php'); // FPDI
    $pdf = new \setasign\Fpdi\Fpdi();
    $pageCount = $pdf->setSourceFile($filePath);
    $tpl = $pdf->importPage(1);
    $size = $pdf->getTemplateSize($tpl);

    $width = $size['width'];
    $height = $size['height'];

    return ($width >= $minWidthMM && $height >= $minHeightMM);
}

// SQL-Datei importieren, falls Tabellen fehlen
function importSQL($sqlFile){
    global $db;
    $stmt = $db->query("SHOW TABLES LIKE 'admin'");
    if($stmt->rowCount() == 0){
        $sql = file_get_contents($sqlFile);
        $db->exec($sql);
    }
}

// Import beim ersten Start
importSQL('standardkonfigurator.sql');
?>
