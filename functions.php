<?php
require_once 'config_env.php';

/**
 * Prüft, ob Admin eingeloggt ist
 */
function checkAdmin() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: index.php");
        exit;
    }
}

/**
 * Zufälligen Token generieren
 */
function generateToken($length = 16) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Prüft PDF-Größe (erste Seite) mit FPDI
 */
function checkPDFSize($filePath, $minWidthMM, $minHeightMM) {

    // FPDI nur laden, wenn benötigt
    require_once __DIR__ . '/vendor/autoload.php';

    $pdf = new \setasign\Fpdi\Fpdi();
    $pageCount = $pdf->setSourceFile($filePath);

    if ($pageCount < 1) {
        return false;
    }

    $tpl = $pdf->importPage(1);
    $size = $pdf->getTemplateSize($tpl);

    $widthMM  = $size['width'];
    $heightMM = $size['height'];

    return ($widthMM >= $minWidthMM && $heightMM >= $minHeightMM);
}

/**
 * Importiert SQL-Datei beim ersten Start
 */
function importSQL($sqlFile) {
    global $db;

    if (!file_exists($sqlFile)) {
        return;
    }

    $stmt = $db->query("SHOW TABLES");
    if ($stmt->rowCount() > 0) {
        return;
    }

    $sql = file_get_contents($sqlFile);
    $db->exec($sql);
}

// SQL-Import automatisch ausführen
importSQL(__DIR__ . '/standardkonfigurator.sql');
